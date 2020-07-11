<?php

namespace Modules\ContactEmails\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

/**
 * Class ContactEmailsRequest
 * @package Modules\ContactEmails\Http\Requests
 */
class CreateContactEmailsRequest extends Request
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
        $id = $this->route()->parameter('contactemail');

        $company = session()->get('currentCompany');



        return [
            'email' => [
                'max:255',
                'required',
                'email',
                Rule::unique('contact_email')->where(function($query) use ($company){
                    $query->where('company_id','=',$company->id);
                }),
                Rule::unique('contacts')->where(function($query) use ($company){ // dont allow to create seconday email where e-mail are already there
                    $query->where('company_id','=',$company->id);
                })
            ]
        ];
    }

}
