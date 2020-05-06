<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderSkuResource extends JsonResource
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
        $data['sku'] = $this->sku;
        $data['merchant'] = $this->merchant;
        $data['store'] = $this->store;
        $data['express'] = $this->express;
        return $data;
    }
}
