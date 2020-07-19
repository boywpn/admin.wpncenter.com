<?php

namespace Modules\Job2\Jobs\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class PaymentRequest
 * @package Modules\Payments\Http\Requests
 */
class JobsProRequest extends Request
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
            'type_id' => 'required',
            'member_id' => 'required',
            'username_id' => 'required',
        ];
    }
}
