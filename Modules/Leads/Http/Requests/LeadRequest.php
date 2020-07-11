<?php

namespace Modules\Leads\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class LeadRequest
 * @package Modules\Leads\Http\Requests
 */
class LeadRequest extends Request
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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'sometimes|nullable|email',
            'secondary_email' => 'sometimes|nullable|email',
            'annual_revenue' => 'sometimes|nullable|numeric',
            'website' => 'sometimes|nullable|url',
            'no_of_employees' => 'sometimes|nullable|numeric'
        ];
    }
}
