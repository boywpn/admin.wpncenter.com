<?php

namespace Modules\Platform\User\Http\Requests;

use App\Http\Requests\Request;

/**
 * help - https://laravel-news.com/unique-and-exists-validation
 *
 * Class RoleUpdateRequest
 * @package Modules\Platform\User\Http\Requests
 */
class RoleUpdateRequest extends Request
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

        $id = $this->route()->parameter('role');

        return [
            'display_name' => 'max:255|required|unique:roles,display_name,'. $id ,
            'name' => 'max:255|required|unique:roles,name,' . $id,
            'guard_name' => 'required'
        ];
    }
}
