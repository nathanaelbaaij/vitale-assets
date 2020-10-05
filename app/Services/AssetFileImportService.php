<?php
/**
 * Created by PhpStorm.
 * User: waar0003
 * Date: 24-11-2018
 * Time: 17:35
 */

namespace App\Services;

use App\Asset;
use App\AssetProperty;
use App\Category;
use App\Depth;
use App\Exceptions\AssetImportException;

class AssetFileImportService
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var Category
     */
    private $parentCategory;

    private $data;

    /**
     * @var AssetFileImportListener
     */
    private $listener;

    /**
     * @param AssetFileImportListener $listener
     */
    public function setListener(AssetFileImportListener $listener)
    {
        $this->listener = $listener;
    }


    /**
     * @param string $content
     * @throws AssetImportException
     */
    public function setData(string $content): void
    {
        // Reset the data fields
        $this->name = null;
        $this->data = null;
        $this->parentCategory = null;

        $data = json_decode($content);

        if (!$data) {
            throw new AssetImportException('File data is not json');
        }
        if (!property_exists($data, 'type')) {
            throw new AssetImportException('Json object does not contain a type property');
        }
        if ( $data->type != 'FeatureCollection') {
            throw new AssetImportException("Type property is not a FeatureCollection, but $data->type");
        }
        if (!property_exists($data->crs->properties, 'name')) {
            throw new AssetImportException('Json object does not contain a crs->properties->name property');
        }
        if ($data->crs->properties->name != 'urn:ogc:def:crs:EPSG::3857') {
            throw new AssetImportException('Geojson CRS is not urn:ogc:def:crs:EPSG::3857');
        }
        if (!property_exists($data, 'name')) {
            throw new AssetImportException('Json object does not contain a name property');
        }
        if (!property_exists($data, 'features')) {
            throw new AssetImportException('Json object does not contain a feature collection');
        }
        //COULD: validate CRS

        $this->data = $data;
        $this->name = $data->name;

   }


    /**
     * Parses the data in the file and record any found assets in the database
     * @throws AssetImportException
     */
    public function parse(): void
    {
        // Find the parent category
        $cat = null;
        foreach (explode("_", $this->name) as $c_name) {
            $cat = $this->findOrCreateCategory($cat, $c_name);
        }
        $this->parentCategory = $cat;

        foreach ($this->data->features as $feature) {
            $insert_cat = $this->parentCategory;

            if (property_exists($feature->properties, 'type')) {
                $insert_cat = $this->findOrCreateCategory($insert_cat, $feature->properties->type);
                unset($feature->properties->type); // ...so it won't be inserted as a property
            }

            $name = '[NO_NAME]';
            if (property_exists($feature->properties, 'name') && $feature->properties->name != null) {
                $name = $feature->properties->name;
                unset($feature->properties->name); // ...so it won't be inserted as a property
            }

            $description = null;
            if (property_exists($feature->properties, 'description')) {
                $description = $feature->properties->description;
                unset($feature->properties->description); // ...so it won't be inserted as a property
            }

            $asset_data = [
                'name' => $name,
                'description' => $description,
                'geometry_type' => $feature->geometry->type,
                'geometry_coordinates' => json_encode($feature->geometry->coordinates),
            ];

            if ( $this->asset_exists($insert_cat, $asset_data)) {
                $this->report_asset_skipped($asset_data);
            } else {
                $new_asset = $insert_cat->assets()->save(new Asset($asset_data));
                $this->createDepths($feature, $new_asset);
                $this->createAssetProperties($feature, $new_asset);

                $this->report_asset_inserted($new_asset);
            }

        }
    }

    /**
     * Finds or creates one category as child of the given parent category
     *
     * @param Category $cat Parent category
     * @param string $cat_name Name of the category to be found or created
     * @return Category|null
     */
    protected function findOrCreateCategory($cat, $cat_name)
    {
        $result = null;
        if ($cat == null) {
            $result = Category::whereNull('parent_id')->where('name', '=', $cat_name)->first();
            if (!$result) {
                $result = Category::create([
                    'name' => $cat_name
                ]);
            }
        } else {
            $result = $cat->children()->where('name', '=', $cat_name)->first();
            if (!$result) {
                $result = $cat->children()->save(
                    new Category([
                        'name' => $cat_name
                    ])
                );
            }
        }
        return $result;
    }

    /**
     * @param $feature
     * @param Asset $asset
     * @throws AssetImportException
     */
    protected function createDepths($feature, Asset $asset)
    {
        foreach ($feature->properties as $property => $waterDepth) {

            if (starts_with($property, "WD_") ) {

                if (!is_null($waterDepth)) {
                    $parts = $this->parseDepthPropertyName( $property );
                    $waterDepth = $this->parseDepthValue($waterDepth);

                    $asset->depths()->save(new Depth([
                        'breach_location_id' => $parts[1],
                        'load_level_id' => $parts[2],
                        'water_depth' => $waterDepth,
                    ]));
                }

                unset($feature->properties->$property); // ...so it won't be inserted as a property
            }
        }
    }

    /**
     * @param string $propertyName
     * @return array
     * @throws AssetImportException
     */
    private function parseDepthPropertyName(string $propertyName) : array
    {
        $parts = explode("_", $propertyName);
        //dd($parts);
        if ( sizeof($parts) != 3 ) {
            throw new AssetImportException("Illegal depth name $propertyName");
        }

        return $parts;
    }

    /**
     * @param $value
     * @return float
     * @throws AssetImportException
     */
    public function parseDepthValue($value) : float
    {
        if ( !is_numeric($value)) {
            throw new AssetImportException("Value ". $value ." is not numeric");
        }

        return 1.0 * $value;
    }

    /**
     * @param $feature
     * @param Asset $asset
     */
    protected function createAssetProperties($feature, Asset $asset)
    {
        foreach ($feature->properties as $property => $value) {
            $asset->properties()->save(new AssetProperty([
                'name' => $property,
                'value' => str_limit($value, $limit = 187, $end = '...')
            ]));
        }
    }

    private function report_asset_skipped($asset_data)
    {
        if ( $this->listener != null ) {
            $this->listener->assetSkipped($asset_data);
        }
    }

    private function report_asset_inserted($asset)
    {
        if ( $this->listener != null ) {
            $this->listener->assetInserted($asset);
        }
    }

    private function asset_exists(Category $cat, array $asset_data) : bool
    {
        $quantity = $cat->assets()->where("name", '=', $asset_data['name'])
            ->where('geometry_type', '=', $asset_data['geometry_type'])
            ->where('geometry_coordinates', '=', $asset_data['geometry_coordinates'])
            ->count();
        return $quantity > 0;
    }

    /**
     * @return string
     * @throws AssetImportException
     */
    public function getName()
    {
        if ($this->name == null) {
            throw new AssetImportException('Name is not properly set');
        }
        return $this->name;
    }

    /**
     * @return int
     * @throws AssetImportException
     */
    public function getFeatureCount()
    {
        if ($this->data == null) {
            throw new AssetImportException('Featurecollection is not properly set');
        }
        return count($this->data->features);
    }

}