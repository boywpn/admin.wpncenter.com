<?php

namespace Modules\Campaigns\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class CampaignsRequest
 * @package Modules\Campaigns\Http\Requests
 */
class CampaignsRequest extends Request
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
            'name' => 'required' ,
        ];
    }
}
