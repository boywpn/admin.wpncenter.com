<?php

namespace Modules\Platform\MenuManager\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class SaveMenuRequest
 * @package Modules\Platform\MenuManager\Http\Requests
 */
class SaveMenuElementRequest extends Request
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
            'id' => 'required',
            'label' => 'required',
            'url' => 'required',
        ];
    }
}
