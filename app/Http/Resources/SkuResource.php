<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SkuResource extends JsonResource
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
        $data['spu'] = $this->spu;
        $data['tags'] = explode(',',$data['tags']);
        $data['images'] = explode(',',$data['images']);
        $data['skus'] = $this->spu->skus;
        return $data;
    }
}
