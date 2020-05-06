<?php

namespace App\Models;


class Image extends BaseModel
{
    protected $hidden = [
        'user_id','member_id'
    ];
}
