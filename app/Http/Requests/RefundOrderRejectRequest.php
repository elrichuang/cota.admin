<?php

namespace App\Http\Requests;

class RefundOrderRejectRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'memo'=>'required|string|max:190'
        ];
    }
}
