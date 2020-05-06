<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class SliceStoreRequest extends BaseRequest
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
