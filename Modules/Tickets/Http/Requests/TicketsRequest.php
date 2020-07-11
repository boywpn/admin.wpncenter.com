<?php

namespace Modules\Tickets\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class TicketsRequest
 * @package Modules\Tickets\Http\Requests
 */
class TicketsRequest extends Request
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
            'name' => 'required' ,
        ];
    }
}
