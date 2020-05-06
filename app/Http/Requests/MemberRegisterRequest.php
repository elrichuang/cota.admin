<?php

namespace App\Http\Requests;


class MemberRegisterRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required_if:type,password|string|min:3|unique:members,nickname',
            'email' => 'required_if:type,email|unique:members,email|string|email',
            'password' => 'required_if:type,email,phone,password|alpha_dash|min:6|string',
            'phone' => 'required_if:type,phone,code|unique:members,phone|string',
            'code_key'=>'required_if:type,code|string',
            'code' => 'required_if:type,code|string'
        ];
    }
}
