<?php

namespace Modules\Core\Boards\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class PaymentRequest
 * @package Modules\Payments\Http\Requests
 */
class BoardsUsersRequest extends Request
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
            'username' => 'required',
            'password' => 'required',
            'board_id' => 'required',
        ];
    }
}
