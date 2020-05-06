<?php

namespace App\Models;

class UserLog extends BaseModel
{
    public function user()
    {
        return $this->hasOne('App\Models\User','id','user_id');
    }
}
