<?php

namespace Modules\Platform\Settings\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class DateformatSettingsRequest
 * @package Modules\Platform\Settings\Http\Requests
 */
class TimeformatSettingsRequest extends Request
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
            'details' => 'required',
            'js_details' => 'required',
        ];
    }
}
