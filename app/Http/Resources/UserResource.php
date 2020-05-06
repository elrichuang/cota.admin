<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
        $data['super_admin'] = boolval($data['super_admin']);
        //为应对vue-element-admin权限要求临时固定admin
        $data['roles'] = ['admin'];//$this->resource->roles()->allRelatedIds()->toArray();
        $roles_ids = $this->roles()->allRelatedIds()->toArray();
        $data['roles_ids'] = $roles_ids?$roles_ids[0]:'';
        return $data;
    }
}
