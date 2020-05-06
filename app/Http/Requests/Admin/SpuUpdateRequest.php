<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class SpuUpdateRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id' => 'required|integer',
            'store_id' => 'required|integer',
            'brand_id' => 'required|integer',
            'name' => 'required|string',
            'spu_no' => 'nullable|string',
            'thumb' => 'nullable|string',
            'num_sort' => 'integer',
        ];
    }
}
