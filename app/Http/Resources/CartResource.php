<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
        $data['member'] = $this->member;
        $data['merchant'] = $this->store->merchant;
        $data['store'] = $this->store;
        $data['spu'] = $this->sku->spu;
        $data['sku'] = $this->sku;
        return $data;
    }
}
