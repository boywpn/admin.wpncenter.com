<?php

namespace Modules\ServiceContracts\Http\Controllers\Settings;

use Modules\ServiceContracts\Datatables\Settings\ServiceContractStatusDatatable;
use Modules\ServiceContracts\Entities\ServiceContractStatus;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class StatusController extends ModuleCrudController
{
    protected $datatable = ServiceContractStatusDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = ServiceContractStatus::class;

    protected $moduleName = 'servicecontracts';

    protected $permissions = [
        'browse' => 'servicecontracts.settings',
        'create' => 'servicecontracts.settings',
        'update' => 'servicecontracts.settings',
        'destroy' => 'servicecontracts.settings'
    ];


    protected $settingsBackRoute = 'servicecontracts.servicecontracts.index';

    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];

    protected $languageFile = 'servicecontracts::servicecontracts.status';

    protected $routes = [
        'index' => 'servicecontracts.status.index',
        'create' => 'servicecontracts.status.create',
        'show' => 'servicecontracts.status.show',
        'edit' => 'servicecontracts.status.edit',
        'store' => 'servicecontracts.status.store',
        'destroy' => 'servicecontracts.status.destroy',
        'update' => 'servicecontracts.status.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
