<?php

namespace Modules\Platform\User\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class RoleCreateRequest
 * @package Modules\Platform\User\Http\Requests
 */
class RoleCreateRequest extends Request
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
            'display_name' => 'required|unique:roles|max:255',
            'name' => 'required|unique:roles|max:255',
            'guard_name' => 'required'
        ];
    }
}
