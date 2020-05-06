<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class MerchantUpdateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $merchant = $this->route('merchant');
        return [
            'nickname'=>'required|string|unique:merchants,nickname,'.$merchant->id,
            'phone' => [
                'string',
                'nullable',
                'regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199)\d{8}$/',
                'unique:merchants,phone,'.$merchant->id
            ],
            'organization_name'=>'nullable|string',
            'logo'=>'nullable|string',
            'contact_man'=>'nullable|string',
            'contact_email'=>'nullable|string',
            'contact_tel'=>'nullable|string',
            'contact_address'=>'nullable|string',
            'weixin_pay_sub_mch_id'=>'nullable|string',
            'alipay_appid'=>'nullable|string',
            'alipay_app_secret'=>'nullable|string',
            'tg_account'=>'nullable|string',
            'tg_key'=>'nullable|string',
            'memo'=>'nullable|string',
        ];
    }
}
