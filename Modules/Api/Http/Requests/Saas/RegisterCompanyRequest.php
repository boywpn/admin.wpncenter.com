<?php

namespace Modules\Api\Http\Requests\Saas;

use App\Http\Requests\Request;

/**
 * Register Company Request
 *
 * Class RegisterCompanyRequest
 * @package Modules\Api\Http\Requests\Saas
 */
class RegisterCompanyRequest extends Request
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
            'company_name' => 'required',
            'api_secret' => 'required',
            'user_first_name' => 'required',
            'user_email' => 'required',
        ];
    }
}
