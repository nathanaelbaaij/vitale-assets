<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewspostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->array = [
            'id' => $this->id,
            'title' => $this->title,
            'message' => $this->message,
            'user_id' => $this->user_id,
            'news_category_id' => $this->news_category_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        //return results
        return $this->array;
    }
}
