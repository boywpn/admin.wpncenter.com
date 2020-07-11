<?php

namespace Modules\Platform\Core\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class AdvancedViewRequest
 * @package Modules\Platform\Core\Http\Requests
 */
class AdvancedViewRequest extends Request
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
            'view_name' => 'required',
            'module_name' => 'required',
        ];
    }
}
