<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class ExpressStoreRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required|string|unique:expresses',
            'alias'=>'required|string|unique:expresses',
        ];
    }
}
