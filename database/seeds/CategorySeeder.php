<?php

use App\Exceptions\CategoryImportException;
use Illuminate\Database\Seeder;
use App\Category;
use Illuminate\Support\Facades\Storage;

/**
 * Class CategorySeeder
 */
class CategorySeeder extends Seeder
{
    /**
     * @var string
     */
    protected $path = "import/categorieen.json";

    /**
     * this method starts the seeder and loops through the csv file
     * @throws CategoryImportException
     */
    public function run()
    {
        //Get the file data and parse it as json
        $data = json_decode(Storage::get($this->path));
        if (!$data) {
            throw new CategoryImportException('File data is not json');
        }

        //loop through the content
        foreach ($data as $cat) {
            $this->parseCategory($cat);
        }
    }

    /**
     * Parses one single Category json object, and its children recursively
     *
     * @param $cat The json object (decode into an associate array)
     * @param null $parent The parent Category model. If null, $cat is a root category
     * @param int $level The amount of spaces to add before the printed line in the console as an indication of the
     *          nesting of categories
     * @throws CategoryImportException
     */
    private function parseCategory($cat, $parent = null, $level=0)
    {
        if (!property_exists($cat, 'name')) {
            throw new CategoryImportException('Json object does not contain a name property');
        }
        $data = [
            "name" => $cat->name
        ];
        if (property_exists($cat, 'description')) {
            $data['description'] = $cat->description;
        }
        if (property_exists($cat, 'threshold')) {
            $data['threshold'] = $cat->threshold;
        }
        if (property_exists($cat, 'symbol')) {
            $data['symbol'] = $cat->symbol;
        }

        if (!$parent) {
            $category_model = Category::create($data);
        } else {
            $category_model = $parent->children()->save(new Category($data));
        }

        $this->command->info( str_repeat("  ", $level) . $data['name']);

        if (property_exists($cat, 'children')) {
            foreach ($cat->children as $child) {
                $this->parseCategory($child, $category_model, $level+1);
            }
        }
    }
}
