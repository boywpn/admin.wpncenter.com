<?php

namespace Modules\Assets\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class AssetsRequest
 * @package Modules\Assets\Http\Requests
 */
class AssetsRequest extends Request
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
            'name' => 'required' ,'tag_number' => 'required' ,
        ];
    }
}
