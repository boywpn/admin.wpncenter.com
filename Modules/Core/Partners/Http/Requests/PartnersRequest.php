<?php

namespace Modules\Core\Partners\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class AccountsRequest
 * @package Modules\Accounts\Http\Requests
 */
class PartnersRequest extends Request
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
            'website' => 'required',
        ];
    }
}
