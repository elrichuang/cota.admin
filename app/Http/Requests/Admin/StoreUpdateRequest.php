<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class StoreUpdateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $store = $this->route('store');
        return [
            'merchant_id' => 'required|integer',
            'nickname'=>'required|string|unique:stores,nickname,'.$store->id,
            'name'=>'required|string',
            'weixin_qrcode_image'=>'nullable|string',
            'weixin_share_content'=>'nullable|string',
            'thumb'=>'nullable|string',
        ];
    }
}
