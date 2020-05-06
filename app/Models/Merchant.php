<?php

namespace App\Models;

class Merchant extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'nickname',
        'phone',
        'password',
        'organization_name',
        'logo',
        'type',
        'contact_man',
        'contact_email',
        'contact_tel',
        'contact_address',
        'weixin_pay_sub_mch_id',
        'alipay_appid',
        'alipay_app_secret',
        'tg_account',
        'tg_key',
        'for_test',
        'memo'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    public function stores()
    {
        return $this->hasMany(Store::Class);
    }
}
