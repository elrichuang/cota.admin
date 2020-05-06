<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class UserStoreRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|between:2,20',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'super_admin' => 'boolean',
            'status' => 'required',
            'roles_ids' => 'required|array'
        ];
    }

    public function attributes()
    {
        return [
            'super_admin'=>'是否超级管理员',
            'status' => '状态',
            'roles_ids' => '角色'
        ];
    }
}
