<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class RoleUpdateRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $role = $this->route('role');
        return [
            'name' => 'required|string|unique:roles,name,'.$role->id,
            'view_abilities_ids' => 'required',
        ];
    }
}
