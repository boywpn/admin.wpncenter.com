<?php

namespace Modules\ContactEmails\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;
use Modules\ContactEmails\Entities\ContactEmail;

/**
 * Class ContactEmailsRequest
 * @package Modules\ContactEmails\Http\Requests
 */
class UpdateContactEmailsRequest extends Request
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

        $contactEmail = ContactEmail::findOrFail($id);

        return [
            'email' => [
                'max:255',
                'required',
                'email',
                Rule::unique('contact_email')->ignore($id)->where(function($query) use ($company){
                   $query->where('company_id','=',$company->id);
                }),
                Rule::unique('contacts')->ignore($contactEmail->contact_id)->where(function($query) use ($company){
                    $query->where('company_id','=',$company->id);
                }),
            ]
        ];
    }

}
