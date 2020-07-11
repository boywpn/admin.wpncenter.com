<?php

namespace Modules\Platform\User\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class ChangePasswordRequest
 * @package Modules\Platform\User\Http\Requests
 */
class UserChangePasswordRequest extends Request
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
