<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class AbilityUpdateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $ability = $this->route('ability');
        return [
            'parent_id' => 'integer',
            'name' => 'required|string',
            'alias' => 'nullable|string|unique:abilities,alias,'.$ability->id,
            'remark' => 'string',
            'url' => 'required_if:use_url,true|nullable|string',
            'status' => 'required',
            'type' => 'string',
            'icon' => 'required_if:type,view|nullable|string',
            'num_sort'=>'required|integer',
            'show_on_menu'=>'boolean',
            'use_url'=>'boolean'
        ];
    }
}
