<?php

namespace Modules\Core\Username\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class PaymentRequest
 * @package Modules\Payments\Http\Requests
 */
class UsernameRequest extends Request
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
            'board_id' => 'required',
            // 'username' => 'required',
            'code' => 'required|min:2|max:3',
        ];
    }
}
