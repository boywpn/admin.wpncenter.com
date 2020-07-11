<?php

namespace Modules\Platform\Core\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Modules\Platform\Companies\Service\CompanyService;
use Modules\Platform\Core\Helper\SettingsHelper;

class AppBaseController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $companyService = \App::make(CompanyService::class);

        \View::share('tenants', $companyService->getCompanies());


    }
}
