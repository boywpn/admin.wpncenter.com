<?php

namespace Modules\Member\Members\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class PaymentRequest
 * @package Modules\Payments\Http\Requests
 */
class UpdatedMembersRequest extends Request
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
            'username' => 'required',
            'phone' => 'required',
            // 'password' => 'required',
            'agent_id' => 'required',
            'status_id' => 'required',
        ];
    }
}
