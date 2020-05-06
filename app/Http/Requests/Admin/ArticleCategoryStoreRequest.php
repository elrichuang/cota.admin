<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class ArticleCategoryStoreRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'parent_id' => 'required|integer',
            'title' => 'required|string',
            'num_sort' => 'integer'
        ];
    }
}
