<?php

namespace Modules\Api\Http\Requests;

use App\Http\Requests\Request;

/**
 *
 * Class TicketApiRequest
 *
 * @package Modules\Api\Http\Requests
 */
class TicketApiRequest extends Request
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
        ];
    }
}
