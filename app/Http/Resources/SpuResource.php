<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SpuResource extends JsonResource
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
        $data['store'] = $this->store;
        $data['brand'] = $this->brand;
        $data['category'] = $this->category;
        $data['skus'] = $this->skus;
        return $data;
    }
}
