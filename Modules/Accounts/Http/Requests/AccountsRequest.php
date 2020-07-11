<?php

namespace Modules\Accounts\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class AccountsRequest
 * @package Modules\Accounts\Http\Requests
 */
class AccountsRequest extends Request
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
            'name' => 'required',
            'email' => 'required|email',
            'secondary_email' => 'sometimes|nullable|email',


        ];
    }
}
