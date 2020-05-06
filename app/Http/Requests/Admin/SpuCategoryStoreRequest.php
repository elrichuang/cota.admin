<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class SpuCategoryStoreRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'store_id' => 'required|integer',
            'parent_id' => 'required|integer',
            'title'=>'required|string',
            'thumb'=>'string',
            'num_sort'=>'integer',
        ];
    }
}
