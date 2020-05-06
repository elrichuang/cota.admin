<?php

namespace App\Http\Middleware;

use Illuminate\Contracts\Encryption\Encrypter as EncrypterContract;
use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array
     */
    protected $except = [
        //
    ];

    public function __construct(EncrypterContract $encrypter)
    {
        parent::__construct($encrypter);

        // 前端接口token不加密
        array_push($this->except,config('admin.api_cookie_name'));
    }
}
