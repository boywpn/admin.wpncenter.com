<?php

namespace Modules\Platform\Companies\Service;

use Illuminate\Support\Facades\Cache;
use Modules\Platform\Companies\Entities\Company;
use Modules\Platform\Companies\Repositories\CompanyRepository;

/**
 * Class CompanyService
 * @package Modules\Platform\Companies\Service
 */
class CompanyService
{

    private $companyRepo;

    const COMPANY_CONTEXT_SESSION = 'sessCompanyContext';

    public function __construct(CompanyRepository $repository)
    {
        $this->companyRepo = $repository;
    }

    /**
     * @return mixed
     */
    public function getCompanies()
    {

        $companies = Cache::remember('all_companies',10,function (){

            return Company::orderBy('name', 'asc')->where('is_enabled', true)->get();

        });

        return $companies;
    }

    /**
     * Add Company Id to session
     * @param $id
     * @return null
     */
    public function switchContext($id)
    {

        $company = $this->companyRepo->findWithoutFail($id);

        if (!empty($company)) {
            session()->put(self::COMPANY_CONTEXT_SESSION, $company);
            return $company;
        }
    }

    /**
     * Remove company from session
     */
    public function dropContext()
    {
        session()->remove(self::COMPANY_CONTEXT_SESSION);
    }

}
