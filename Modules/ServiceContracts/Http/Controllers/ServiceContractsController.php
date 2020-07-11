<?php

namespace Modules\ServiceContracts\Http\Controllers;

use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\ServiceContracts\Datatables\ServiceContractDatatable;
use Modules\ServiceContracts\Datatables\Tabs\ServiceContractsDocumentsDatatable;
use Modules\ServiceContracts\Entities\ServiceContract;
use Modules\ServiceContracts\Http\Forms\ServiceContractForm;
use Modules\ServiceContracts\Http\Requests\ServiceContractsRequest;

class ServiceContractsController extends ModuleCrudController
{
    protected $datatable = ServiceContractDatatable::class;
    protected $formClass = ServiceContractForm::class;
    protected $storeRequest = ServiceContractsRequest::class;
    protected $updateRequest = ServiceContractsRequest::class;
    protected $entityClass = ServiceContract::class;

    protected $moduleName = 'servicecontracts';

    protected $permissions = [
        'browse' => 'servicecontracts.browse',
        'create' => 'servicecontracts.create',
        'update' => 'servicecontracts.update',
        'destroy' => 'servicecontracts.destroy'
    ];

    protected $moduleSettingsLinks = [

        ['route' => 'servicecontracts.priority.index', 'label' => 'settings.priority'],
        ['route' => 'servicecontracts.status.index', 'label' => 'settings.status'],


    ];

    protected $settingsPermission = 'servicecontracts.settings';

    protected $relationTabs = [

        'documents' => [
            'icon' => 'storage',
            'permissions' => [
                'browse' => 'documents.browse',
                'update' => 'documents.update',
                'create' => 'documents.create'
            ],
            'datatable' => [
                'datatable' => ServiceContractsDocumentsDatatable::class
            ],
            'route' => [
                'linked' => 'servicecontracts.documents.linked',
                'create' => 'documents.documents.create',
                'select' => 'servicecontracts.documents.selection',
                'bind_selected' => 'servicecontracts.documents.link'
            ],
            'create' => [
                'allow' => true,
                'modal_title' => 'documents::documents.create_new',
                'post_create_bind' => [
                    'relationType' => 'manyToMany',
                    'relatedField' => 'serviceContracts',
                ]
            ],

            'select' => [
                'allow' => true,
                'modal_title' => 'documents::documents.module'
            ],

        ],

    ];

    protected $showFields = [

        'information' => [

            'name' => [
                'type' => 'text',
            ],


            'start_date' => [
                'type' => 'date',
            ],


            'due_date' => [
                'type' => 'date',
            ],


            'owned_by' => [
                'type' => 'assigned_to',
            ],


            'service_contract_priority_id' => [
                'type' => 'manyToOne',
                'relation' => 'serviceContractPriority',
                'column' => 'name'
            ],


            'service_contract_status_id' => [
                'type' => 'manyToOne',
                'relation' => 'serviceContractStatus',
                'column' => 'name'
            ],

            'account_id' => [
                'type' => 'manyToOne',
                'relation' => 'account',
                'column' => 'name',
                'dont_translate' => true
            ],

        ],


        'notes' => [

            'notes' => [
                'type' => 'text',
                'col-class' => 'col-lg-12'
            ],

        ],


    ];

    protected $languageFile = 'servicecontracts::servicecontracts';

    protected $routes = [
        'index' => 'servicecontracts.servicecontracts.index',
        'create' => 'servicecontracts.servicecontracts.create',
        'show' => 'servicecontracts.servicecontracts.show',
        'edit' => 'servicecontracts.servicecontracts.edit',
        'store' => 'servicecontracts.servicecontracts.store',
        'destroy' => 'servicecontracts.servicecontracts.destroy',
        'update' => 'servicecontracts.servicecontracts.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
