<?php

namespace Modules\Quotes\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class QuotesRequest
 * @package Modules\Quotes\Http\Requests
 */
class QuotesRequest extends Request
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
            'discount' =>  'sometimes|nullable|numeric|min:0',
            'delivery_cost' =>  'sometimes|nullable|numeric|min:0',
            'rows.*.product_name' => 'required',
            'rows.*.price' => 'required|numeric|min:0.1',
            'rows.*.quantity' => 'required|numeric|min:1',
        ];
    }
}
