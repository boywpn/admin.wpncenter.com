<?php

namespace Modules\Platform\Settings\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class OutgoingServerTestMailRequest
 * @package Modules\Platform\Settings\Http\Requests
 */
class OutgoingServerTestMailRequest extends Request
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
            'email' => 'required|email',
            'message' => 'required',
        ];
    }
}
