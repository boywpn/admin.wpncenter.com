<?php

namespace Modules\Core\Owners\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class AccountsRequest
 * @package Modules\Accounts\Http\Requests
 */
class OwnersRequest extends Request
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
            'code' => 'required',
        ];
    }
}
