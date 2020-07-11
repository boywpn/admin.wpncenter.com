<?php

namespace Modules\Leads\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

/**
 * Class CreateLeadRequest
 * @package Modules\Leads\Http\Requests
 */
class CreateLeadRequest extends Request
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
                'max:255',
                'sometimes',
                'email',
                Rule::unique('leads')->where(function ($query) use ($company) {
                    $query->where('company_id', '=', $company->id);
                }),
                Rule::unique('lead_email')->where(function ($query) use ($company) {
                    $query->where('company_id', '=', $company->id);
                }),
                Rule::unique('contacts')->where(function ($query) use ($company) {
                    $query->where('company_id', '=', $company->id);
                }),
            ],
            'secondary_email' => 'sometimes|nullable|email',
            'annual_revenue' => 'sometimes|nullable|numeric',
            'website' => 'sometimes|nullable|url',
            'no_of_employees' => 'sometimes|nullable|numeric'
        ];
    }
}
