<?php

namespace Modules\Core\Games\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class AccountsRequest
 * @package Modules\Accounts\Http\Requests
 */
class GamesTypesRequest extends Request
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
            'game_id' => 'required',
            'start_comm' => 'required',
            'taking' => 'required',
            'name' => 'required',
            'code' => 'required',
        ];
    }
}
