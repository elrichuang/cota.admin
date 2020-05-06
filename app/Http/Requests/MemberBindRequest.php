<?php

namespace App\Http\Requests;


class MemberBindRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => 'required|string',
            'code_key'=>'required|string',
            'code' => 'required|string'
        ];
    }
}
