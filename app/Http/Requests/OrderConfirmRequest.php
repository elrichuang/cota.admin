<?php

namespace App\Http\Requests;


class OrderConfirmRequest extends OrderCheckCartRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();

        // 添加确认订单的规则
        $rules['delivery_type'] = 'required|in:express,take_up'; //配送方式
        $rules['member_address_id'] = 'required_if:delivery_type,express|integer';
        $rules['memo'] = 'string';

        return $rules;
    }
}
