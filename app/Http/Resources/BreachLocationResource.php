<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BreachLocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "type" => "Feature",
            "properties" => [
                "Asset" => "Breachlocation",
                "id" => $this->id,
                "name" => $this->name,
                "code" => $this->code,
                "dykering" => $this->dykering,
                "vnk2" => $this->vnk2,
                'links' => [
                    'self' => route('breaches.show', ['breach' => $this->id]),
                ],
            ],
            "geometry" => [
                "type" => "Point",
                "coordinates" => [$this->xcoord, $this->ycoord]
            ],
        ];
    }
}
