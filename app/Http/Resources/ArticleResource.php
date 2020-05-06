<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['category'] = $this->category;
        $data['on_top'] = $this->on_top_at ? true : false;
        $data['recommend'] = $this->recommend_at ? true : false;
        return $data;
    }
}
