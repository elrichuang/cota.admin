<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class MemberUpdateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $member = $this->route('member');
        return [
            'name' => 'required|string',
            'nickname' => 'required|string|unique:members,nickname,'.$member->id,
            'email' => 'string|email|unique:members,email,'.$member->id,
            'phone' => [
                'string',
                'nullable',
                'regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199)\d{8}$/',
                'unique:members,phone,'.$member->id
            ],
            'avatar'=>'required|string',
            'sex'=>'required|integer',
            'password' => 'confirmed|nullable|min:6',
        ];
    }

    public function attributes()
    {
        return [
            'avatar'=>'头像',
            'sex' => '性别'
        ];
    }
}
