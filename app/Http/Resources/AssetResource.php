<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\BreachLocation;
use Auth;

class AssetResource extends JsonResource
{
    private $array = [];

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        //setup geojson
        $this->array = [
            "type" => "Feature",
            "properties" => [
                "Asset" => trim($this->name),
                'id' => $this->id,
                'name' => trim($this->name),
                'description' => $this->description,
                'category' => [
                    'name' => $this->category->name,
                    'threshold' => $this->floatToDecimal($this->category->threshold, 2) . 'm',
                ],
                'threshold_correction' => $this->floatToDecimal($this->threshold_correction, 2) . 'm',
                'symbol' => $this->category->symbol,
                'image' => $this->image,
                'links' => [
                    'self' => $this->when(Auth::user()->can('asset-list'), route('assets.show', ['assets' => $this->id])),
                    'edit' => $this->when(Auth::user()->can('asset-edit'), route('assets.edit', ['assets' => $this->id])),
                ],
                'state_color' => $this->computeState(),
                'current_water_depth' => $this->floatToDecimal($this->getCurrentWaterDepth(), 2) . 'm',
            ],
            'geometry' => [
                'type' => $this->geometry_type,
                'coordinates' => json_decode($this->geometry_coordinates)
            ],
        ];

        //return results
        return $this->array;
    }

    /**
     * @param $input
     * @param $decimals
     * @return string
     */
    public function floatToDecimal($input, $decimals)
    {
        return number_format((float)$input, $decimals, ',', '');
    }
}
