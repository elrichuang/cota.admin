<?php

namespace App\Http\Requests;

class MemberProfileUpdateRequest extends BaseRequest
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
            'email' => 'required_if:type,email|unique:members,email',
            'phone' => 'required_if:type,phone,code|unique:members,phone',
            'avatar' => 'string',
            'name' => 'string',
            'sex' => 'integer',
        ];
    }
}
