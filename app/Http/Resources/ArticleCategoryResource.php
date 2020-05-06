<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'parent' => $this->parent,
            'parent_id' => $this->parent_id,
            'title' => $this->title,
            'num_sort' => $this->num_sort,
            'created_at' => $this->created_at,
        ];
    }
}
