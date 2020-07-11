<?php

namespace Modules\Calendar\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class CalendarRequest
 * @package Modules\Calendar\Http\Requests
 */
class CalendarRequest extends Request
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
            'default_view' => 'required',
            'first_day' => 'required',
            'day_start_at' => 'required'
        ];
    }
}
