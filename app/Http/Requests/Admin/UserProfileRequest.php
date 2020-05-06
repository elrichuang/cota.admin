<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class UserProfileRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'avatar'=>'string|nullable',
            'old_password' => 'string|nullable|required_with:password',
            'password' => 'string|nullable|confirmed|min:6|required_with:old_password',
        ];
    }
}
