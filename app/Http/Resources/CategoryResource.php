<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $array = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'threshold' => $this->threshold,
            'amount_of_assets' => count($this->assets),
            'parent_id' => $this->parent_id,
            'parent_cat' => null,
            'children_cat' => $this->collection($this->children),
            'assets' => AssetResource::collection($this->assets),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        if ($this->parent != null) {
            array_set($array, 'parent_cat', $this->parent);
        }

        return $array;
    }
}
