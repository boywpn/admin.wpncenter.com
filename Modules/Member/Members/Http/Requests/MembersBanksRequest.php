<?php

namespace Modules\Member\Members\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class PaymentRequest
 * @package Modules\Payments\Http\Requests
 */
class MembersBanksRequest extends Request
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
            'member_id' => 'required',
            'bank_id' => 'required',
            'bank_account' => 'required',
            'bank_number' => 'required',
        ];
    }
}
