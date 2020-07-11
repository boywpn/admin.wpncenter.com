<?php

namespace Modules\Platform\User\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class GroupCreateRequest
 * @package Modules\Platform\User\Http\Requests
 */
class GroupCreateRequest extends Request
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
            'name' => 'required|max:255'
        ];
    }
}
