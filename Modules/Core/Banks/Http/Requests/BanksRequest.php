<?php

namespace Modules\Core\Banks\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class PaymentRequest
 * @package Modules\Payments\Http\Requests
 */
class BanksRequest extends Request
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
            'bank_id' => 'required',
            'username' => 'required',
            'password' => 'required',
            'account' => 'required',
            'number' => 'required',
            'phone' => 'required'
        ];
    }
}
