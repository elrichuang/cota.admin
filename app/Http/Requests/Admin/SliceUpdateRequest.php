<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class SliceUpdateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required|string',
        ];
    }
}
