<?php

namespace Modules\Core\BanksPartners\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class PaymentRequest
 * @package Modules\Payments\Http\Requests
 */
class BanksPartnersRequest extends Request
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
            'name' => '',
            'partner_id' => 'required',
            'bank_id' => 'required',
            'member_status_id' => 'required'
        ];
    }
}
