<?php

namespace App\Http\Requests;


class MemberAddressStoreRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'consignee'=>'required|string',
            'phone' => [
                'required',
                'string',
                'regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199)\d{8}$/'
            ],
            'province'=>'required|string',
            'city'=>'required|string',
            'address'=>'required|string'
        ];
    }
}
