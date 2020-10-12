<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class RoleStoreRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|unique:roles',
            'view_abilities_ids' => 'required',
        ];
    }
}
