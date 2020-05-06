<?php

namespace App\Http\Requests;

class OrderCancelRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'no' => 'required|string'
        ];
    }
}
