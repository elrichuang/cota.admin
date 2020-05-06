<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class StoreStoreRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'merchant_id' => 'required|integer',
            'nickname'=>'required|string|unique:stores',
            'name'=>'required|string',
            'weixin_qrcode_image'=>'nullable|string',
            'weixin_share_content'=>'nullable|string',
            'thumb'=>'nullable|string',
        ];
    }
}
