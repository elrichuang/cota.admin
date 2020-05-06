<?php

namespace App\Http\Requests;


class OrderPayRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'pay_type' => 'required|in:weixin,alipay,tgposp',
            'no'=>'required|string',
            'trade_type'=>'required|in:JSAPI,APP',
            'client_type'=>'required|in:h5,miniapp,app'
        ];
    }
}
