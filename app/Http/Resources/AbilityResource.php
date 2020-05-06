<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AbilityResource extends JsonResource
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
            'name' => $this->name,
            'icon' => $this->icon,
            'alias' => $this->alias,
            'remark' => $this->remark,
            'status' => $this->status,
            'type' => $this->type,
            'show_on_menu'=>$this->show_on_menu,
            'num_sort' => $this->num_sort,
            'created_at' => $this->created_at
        ];
    }
}
