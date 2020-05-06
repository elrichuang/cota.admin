<?php

namespace App\Models;

class Store extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'merchant_id',
        'nickname',
        'name',
        'weixin_qrcode_image',
        'weixin_share_content',
        'thumb',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    public function merchant()
    {
        return $this->hasOne('App\Models\Merchant','id','merchant_id');
    }

    public function spus()
    {
        return $this->hasMany('App\Models\Spu');
    }
}
