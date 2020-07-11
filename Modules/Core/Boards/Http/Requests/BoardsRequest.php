<?php

namespace Modules\Core\Boards\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class PaymentRequest
 * @package Modules\Payments\Http\Requests
 */
class BoardsRequest extends Request
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
            'user_prefix' => 'required',
            'member_limit' => 'required',
            'board_number' => 'required|max:6|min:2',
            // 'agent_id' => 'required',
            'game_id' => 'required',
        ];
    }
}
