<?php

namespace App\Http\Requests;


class MemberLoginRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required_if:type,password|string',
            'email' => 'required_if:type,email|email',
            'password' => 'required_if:type,email,phone,password|alpha_dash|min:6|string',
            'phone' => 'required_if:type,phone,code|string',
            'code_key'=>'required_if:type,code|string',
            'code' => 'required_if:type,code|string'
        ];
    }
}
