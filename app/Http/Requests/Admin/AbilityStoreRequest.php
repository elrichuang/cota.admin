<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class AbilityStoreRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'parent_id' => 'integer',
            'name' => 'required|string',
            'alias' => 'nullable|string|unique:abilities',
            'remark' => 'string',
            'url' => 'string',
            'status' => 'required',
            'type' => 'string',
            'icon' => 'required_if:type,view|nullable|string',
            'num_sort'=>'required|integer',
            'show_on_menu'=>'boolean',
            'use_url'=>'boolean'
        ];
    }
}
