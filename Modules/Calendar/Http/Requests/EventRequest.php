<?php

namespace Modules\Calendar\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class EventRequest
 * @package Modules\Calendar\Http\Requests
 */
class EventRequest extends Request
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
            'start_date' => 'required',
            'event_priority_id' => 'required',
            'event_status_id' => 'required',
            'description' => 'required'
        ];
    }
}
