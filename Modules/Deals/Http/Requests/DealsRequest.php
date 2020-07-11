<?php

namespace Modules\Deals\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class DealsRequest
 * @package Modules\Deals\Http\Requests
 */
class DealsRequest extends Request
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
