<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class BrandUpdateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'merchant_id' => 'nullable|integer',
            'store_id' => 'nullable|integer',
            'title'=>'required|string',
            'logo'=>'nullable|string'
        ];
    }
}
