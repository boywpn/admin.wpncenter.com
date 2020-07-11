<?php

namespace Modules\Platform\Companies\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class CurrencySettingsRequest
 * @package Modules\Platform\Settings\Http\Requests
 */
class CompanyRequest extends Request
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
            'user_limit' => 'nullable|numeric|min:1',
            'storage_limit' => 'nullable|numeric|min:1'
        ];
    }
}
