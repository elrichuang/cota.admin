<?php

namespace App\Http\Requests;

class CartStoreRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sku_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'payment' => 'required|in:cash,score,both'
        ];
    }
}
