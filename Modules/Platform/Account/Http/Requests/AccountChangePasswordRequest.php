<?php

namespace Modules\Platform\Account\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class AccountChangePasswordRequest
 * @package Modules\Platform\Account\Http\Requests
 */
class AccountChangePasswordRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'password' => 'required|min:6|max:255',
            'password_confirm' => 'required|min:6|max:255|same:password'
        ];
    }
}
