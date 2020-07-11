<?php

namespace Modules\Member\Members\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

/**
 * Class PaymentRequest
 * @package Modules\Payments\Http\Requests
 */
class MembersRequest extends Request
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
        $username = $this->input('username');
        $phone = $this->input('phone');
        return [
            'name' => 'required',
            'username' => [
                'required',
                Rule::unique('member_members')->where(function ($query) use ($username) {
                    $query->where('username', '=', $username);
                }),
            ],
            'phone' => [
                'required',
                Rule::unique('member_members')->where(function ($query) use ($phone) {
                    $query->where('phone', '=', $phone);
                }),
            ],
            'password' => 'required',
            'agent_id' => 'required',
            'status_id' => 'required',
        ];
    }
}
