<?php

namespace Modules\Vendors\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class VendorsRequest
 * @package Modules\Vendors\Http\Requests
 */
class VendorsRequest extends Request
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
            'name' => 'required' ,'email' => 'sometimes|nullable|email' ,'secondary_email' => 'sometimes|nullable|email' ,
        ];
    }
}
