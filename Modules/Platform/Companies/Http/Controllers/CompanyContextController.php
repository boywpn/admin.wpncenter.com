<?php

namespace Modules\Platform\Companies\Http\Controllers;

use Modules\Platform\Companies\Service\CompanyService;
use Modules\Platform\Core\Http\Controllers\AppBaseController;

class CompanyContextController extends AppBaseController
{

    private $companyService;


    public function __construct(CompanyService $companyService)
    {
        parent::__construct();

        $this->companyService = $companyService;

    }

    public function switchCompany($id)
    {

        $company = $this->companyService->switchContext($id);

        \Flash::warning(trans('companies::companies.company_context_changed', ['company' => $company->name]));

        return redirect()->back();
    }

    public function dropContext()
    {

        $this->companyService->dropContext();

        \Flash::warning(trans('companies::companies.company_context_droped'));

        return redirect()->back();
    }
}