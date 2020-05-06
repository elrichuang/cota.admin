<?php

namespace App\Models;


class SliceItem extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slice_id','image','url','num_sort'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    public function slice()
    {
        return $this->hasOne('App\Models\Slice','id','slice_id');
    }
}
