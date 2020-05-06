<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class UserLoginRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
            'captcha_key' => 'required|string',
            'captcha_code' => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'captcha_key' => '图片验证码ID',
            'captcha_code' => '图片验证码',
        ];
    }
}
