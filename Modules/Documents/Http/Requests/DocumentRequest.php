<?php

namespace Modules\Documents\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class DocumentRequest
 * @package Modules\Documents\Http\Requests
 */
class DocumentRequest extends Request
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
            'title' => 'required',
        ];
    }
}
