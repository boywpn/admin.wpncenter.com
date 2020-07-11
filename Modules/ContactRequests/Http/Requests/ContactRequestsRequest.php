<?php

namespace Modules\ContactRequests\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class ContactRequestsRequest
 * @package Modules\ContactRequests\Http\Requests
 */
class ContactRequestsRequest extends Request
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
            'first_name' => 'required', 'last_name' => 'required',
        ];
    }

}
