<?php

namespace Modules\Accounts\Http\Controllers\Settings;

use Modules\Accounts\Datatables\Settings\AccountTypeDatatable;
use Modules\Accounts\Entities\AccountType;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class TypeController extends ModuleCrudController
{
    protected $datatable = AccountTypeDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = AccountType::class;

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

    protected $languageFile = 'accounts::accounts.type';

    protected $routes = [
        'index' => 'accounts.type.index',
        'create' => 'accounts.type.create',
        'show' => 'accounts.type.show',
        'edit' => 'accounts.type.edit',
        'store' => 'accounts.type.store',
        'destroy' => 'accounts.type.destroy',
        'update' => 'accounts.type.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
