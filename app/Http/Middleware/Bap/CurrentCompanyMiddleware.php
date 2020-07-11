<?php

namespace App\Http\Middleware\Bap;

use Closure;
use HipsterJazzbo\Landlord\Facades\Landlord;
use Illuminate\Support\Facades\Auth;
use Modules\Platform\Companies\Entities\Company;
use Modules\Platform\Companies\Service\CompanyService;
use Modules\Platform\Core\Helper\CompanySettings;
use Modules\Platform\Core\Helper\SettingsHelper;

/**
 * Class CurrentCompanyMiddleware
 * @package App\Http\Middleware\Bap
 */
class CurrentCompanyMiddleware
{

    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if (Auth::check()) {

            $user = Auth::user();

            $currentCompany = null;

            $companyConext = session()->get(CompanyService::COMPANY_CONTEXT_SESSION);

            if($user->company != null ){
                Landlord::addTenant('company_id', $user->company->id);

                $currentCompany = $user->company;

            }else if($companyConext){
                Landlord::addTenant('company_id', $companyConext->id);

                $currentCompany = $companyConext;
            }else{

                $firstCompany = Company::first();

                Landlord::addTenant('company_id', $firstCompany->id);

                $currentCompany = $firstCompany;
                
            }

            session()->put('currentCompany',$currentCompany);

        }
        return $next($request);
    }
}
