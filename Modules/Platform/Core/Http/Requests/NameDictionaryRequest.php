<?php

namespace Modules\Platform\Core\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class NameDictionaryRequest
 * @package Modules\Platform\Core\Http\requests
 */
class NameDictionaryRequest extends Request
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
        ];
    }
}
