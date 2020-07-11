<?php

namespace Modules\Platform\Companies\Http\Controllers;

use Modules\Platform\Companies\Datatables\CompaniesDatatable;
use Modules\Platform\Companies\Entities\Company;
use Modules\Platform\Companies\Http\Forms\CompanyForm;
use Modules\Platform\Companies\Http\Requests\CompanyRequest;
use Modules\Platform\Companies\Repositories\CompanyRepository;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Controllers\SettingsCrudController;

class CompanyController extends ModuleCrudController
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $settingsMode = true;

    protected $disableTabs = true;

    protected $moduleName = 'settings';

    protected $permissions = [
        'browse' => 'settings.access',
        'create' => 'settings.access',
        'update' => 'settings.access',
        'destroy' => 'settings.access'
    ];

    protected $entityClass = Company::class;

    protected $datatable = CompaniesDatatable::class;

    protected $formClass = CompanyForm::class;

    protected $storeRequest = CompanyRequest::class;

    protected $updateRequest = CompanyRequest::class;

    protected $repository = CompanyRepository::class;

    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text'],
            'user_limit' => ['type' => 'number'],
            'storage_limit' => ['type' => 'number'],
            'is_enabled' => ['type' => 'checkbox'],
            'description' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];


    protected $languageFile = 'companies::companies';


    protected $routes = [
        'index' => 'settings.companies.index',
        'create' => 'settings.companies.create',
        'show' => 'settings.companies.show',
        'edit' => 'settings.companies.edit',
        'store' => 'settings.companies.store',
        'destroy' => 'settings.companies.destroy',
        'update' => 'settings.companies.update'
    ];
}
