<?php

namespace Modules\Platform\User\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class UserCreateRequest
 * @package Modules\Platform\User\Http\Requests
 */
class UserCreateRequest extends Request
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
            'first_name' => 'required|max:255',
            'email' => 'required|unique:users|max:255',
            'language_id'=>'required',
            'date_format_id'=>'required',
            'time_format_id'=>'required',
            'time_zone'=>'required',
            'theme' => 'required',
            'profile_picture' => 'mimes:jpeg,png'
        ];
    }
}
