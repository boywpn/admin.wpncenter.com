<?php

namespace Modules\Platform\Announcement\Http\Requests;

use App\Http\Requests\Request;

/**
 * Class SaveAnnouncementRequest
 * @package Modules\Platform\Announcement\Http\Requests
 */
class SaveAnnouncementRequest extends Request
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


    public function sanitize()
    {
        $this->all();
    }

    public function rules()
    {
        return [

        ];
    }
}
