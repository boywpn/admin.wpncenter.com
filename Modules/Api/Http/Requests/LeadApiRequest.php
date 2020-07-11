<?php
namespace Modules\Api\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

/**
 * Lead API Request
 *
 * Class LeadApiRequest
 * @package Modules\Api\Http\Requests
 */
class LeadApiRequest extends Request
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


        $companyId = request()->get('company_id');

        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => [
                'max:255',
                'sometimes',
                'email',
                Rule::unique('leads')->where(function ($query) use ($companyId) {
                    $query->where('company_id', '=', $companyId);
                }),
                Rule::unique('lead_email')->where(function ($query) use ($companyId) {
                    $query->where('company_id', '=', $companyId);
                }),
                Rule::unique('contacts')->where(function ($query) use ($companyId) {
                    $query->where('company_id', '=', $companyId);
                }),
            ],
            'secondary_email' => 'sometimes|nullable|email',
            'annual_revenue' => 'sometimes|nullable|numeric',
            'website' => 'sometimes|nullable|url',
            'no_of_employees' => 'sometimes|nullable|numeric',
            'company_id' => 'required'
        ];
    }
}
