<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderCheckCartRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cart_ids'=>'required|string',
            'direct_buy'=>'required|integer',
            'group_buy'=>'required_if:direct_buy,true|integer',
            'sku_id'=>'required_if:direct_buy,true|integer',
            'quantity'=>'required_if:direct_buy,true|integer,min:1',
            'payment'=>'required_if:direct_buy,true|string'
        ];
    }
}
