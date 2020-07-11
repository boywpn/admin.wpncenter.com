<?php

namespace Modules\Calls\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class CallsRequest
 * @package Modules\Calls\Http\Requests
 */
class CallsRequest extends Request
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
            'subject' => 'required', 'phone_number' => 'required', 'duration' => 'required',
        ];
    }

}