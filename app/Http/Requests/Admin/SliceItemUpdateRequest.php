<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class SliceItemUpdateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'slice_id'=>'required|integer',
            'image'=>'required|url',
            'url'=>'nullable|url',
            'num_sort'=>'required|integer'
        ];
    }
}
