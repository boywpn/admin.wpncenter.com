<?php

namespace Modules\ServiceContracts\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class ServiceContractsRequest
 * @package Modules\ServiceContracts\Http\Requests
 */
class ServiceContractsRequest extends Request
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
