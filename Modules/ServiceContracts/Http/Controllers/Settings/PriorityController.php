<?php

namespace Modules\ServiceContracts\Http\Controllers\Settings;

use Modules\ServiceContracts\Datatables\Settings\ServiceContractPriorityDatatable;
use Modules\ServiceContracts\Entities\ServiceContractPriority;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class PriorityController extends ModuleCrudController
{
    protected $datatable = ServiceContractPriorityDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = ServiceContractPriority::class;

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

    protected $languageFile = 'servicecontracts::servicecontracts.priority';

    protected $routes = [
        'index' => 'servicecontracts.priority.index',
        'create' => 'servicecontracts.priority.create',
        'show' => 'servicecontracts.priority.show',
        'edit' => 'servicecontracts.priority.edit',
        'store' => 'servicecontracts.priority.store',
        'destroy' => 'servicecontracts.priority.destroy',
        'update' => 'servicecontracts.priority.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
