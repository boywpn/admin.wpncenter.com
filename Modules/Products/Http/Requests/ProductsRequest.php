<?php

namespace Modules\Products\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class ProductsRequest
 * @package Modules\Products\Http\Requests
 */
class ProductsRequest extends Request
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
            'image_path' => 'mimes:jpeg,png,gif,jpg'
        ];
    }
}
