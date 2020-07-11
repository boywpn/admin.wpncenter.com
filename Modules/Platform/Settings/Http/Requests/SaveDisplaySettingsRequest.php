<?php

namespace Modules\Platform\Settings\Http\Requests;

use App\Http\Requests\Request;
use Modules\Platform\Core\Helper\SettingsHelper;

/**
 * Class SaveDisplaySettingsRequest
 * @package Modules\Platform\Settings\Http\Requests
 */
class SaveDisplaySettingsRequest extends Request
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
            SettingsHelper::S_DISPLAY_APPLICATION_NAME => 'required',
            SettingsHelper::S_DISPLAY_LOGO_UPLOAD => 'mimes:jpg,png,gif,jpeg',
            SettingsHelper::S_DISPLAY_PDF_LOGO_UPLOAD => 'mimes:jpg,png,gif,jpeg'
        ];
    }
}
