<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class ExpressUpdateRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $express = $this->route('express');
        return [
            'name'=>'required|string|unique:expresses,name,'.$express->id,
            'alias'=>'required|string|unique:expresses,alias,'.$express->id,
        ];
    }
}
