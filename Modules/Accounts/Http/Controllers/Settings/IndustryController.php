<?php

namespace Modules\Accounts\Http\Controllers\Settings;

use Modules\Accounts\Datatables\Settings\AccountIndustryDatatable;
use Modules\Accounts\Entities\AccountIndustry;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class IndustryController extends ModuleCrudController
{
    protected $datatable = AccountIndustryDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = AccountIndustry::class;

    protected $moduleName = 'accounts';

    protected $permissions = [
        'browse' => 'accounts.settings',
        'create' => 'accounts.settings',
        'update' => 'accounts.settings',
        'destroy' => 'accounts.settings'
    ];


    protected $settingsBackRoute = 'accounts.accounts.index';

    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];

    protected $languageFile = 'accounts::accounts.industry';

    protected $routes = [
        'index' => 'accounts.industry.index',
        'create' => 'accounts.industry.create',
        'show' => 'accounts.industry.show',
        'edit' => 'accounts.industry.edit',
        'store' => 'accounts.industry.store',
        'destroy' => 'accounts.industry.destroy',
        'update' => 'accounts.industry.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
