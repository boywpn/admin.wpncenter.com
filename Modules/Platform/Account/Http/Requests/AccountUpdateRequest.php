<?php

namespace Modules\Platform\Account\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class AccountUpdateRequest
 * @package Modules\Platform\Account\Http\Requests
 */
class AccountUpdateRequest extends Request
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
            'language_id'=>'required',
            'date_format_id'=>'required',
            'time_format_id'=>'required',
            'time_zone'=>'required',
            'theme' => 'required',
            'profile_picture' => 'mimes:jpeg,png'
        ];
    }
}
