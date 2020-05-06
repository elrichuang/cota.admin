<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class SkuStoreRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'spu_id' => 'required|integer',
            'name' => 'required|string',
            'sku_no' => 'nullable|string',
            'images' => 'nullable|array',
            'price' => 'nullable|numeric',
            'score' => 'nullable|integer',
            'less_price' => 'nullable|numeric',
            'less_score' => 'nullable|integer',
            'cost' => 'nullable|numeric',
            'commission' => 'nullable|numeric',
            'norms' => 'nullable|string',
            'color' => 'nullable|string',
            'material' => 'nullable|string',
            'tags' => 'nullable|array',
            'content' => 'nullable|string',
            'num_stock' => 'nullable|integer',
            'num_sort' => 'integer',
            'status'=>'required|string'
        ];
    }
}
