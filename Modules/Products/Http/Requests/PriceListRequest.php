<?php

namespace Modules\Products\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class PriceListRequest
 * @package Modules\Products\Http\Requests
 */
class PriceListRequest extends Request
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
