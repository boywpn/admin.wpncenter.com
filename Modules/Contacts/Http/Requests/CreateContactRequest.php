<?php

namespace Modules\Contacts\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

/**
 * Class CreateContactRequest
 * @package Modules\Contacts\Http\Requests
 */
class CreateContactRequest extends Request
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

        $company = session()->get('currentCompany');

        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => [
                'required',
                'max:255',
                'email',
                Rule::unique('contacts')->where(function ($query) use ($company) {
                    $query->where('company_id', '=', $company->id);
                }),
                Rule::unique('contact_email')->where(function ($query) use ($company) {
                    $query->where('company_id', '=', $company->id);
                }),
            ],
            'secondary_email' => 'sometimes|nullable|email',
            'profile_picture' => 'mimes:jpeg,png'
        ];
    }
}
