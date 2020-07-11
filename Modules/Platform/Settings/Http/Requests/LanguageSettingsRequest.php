<?php

namespace Modules\Platform\Settings\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class LanguageSettingslRequest
 * @package Modules\Platform\Settings\Http\Requests
 */
class LanguageSettingsRequest extends Request
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
            'language_key' => 'required',
        ];
    }
}
