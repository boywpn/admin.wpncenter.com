<?php

namespace Modules\Invoices\Http\Controllers\Settings;

use Modules\Invoices\Datatables\Settings\InvoiceStatusDatatable;
use Modules\Invoices\Entities\InvoiceStatus;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class StatusController extends ModuleCrudController
{
    protected $datatable = InvoiceStatusDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = InvoiceStatus::class;

    protected $moduleName = 'invoices';

    protected $permissions = [
        'browse' => 'invoices.settings',
        'create' => 'invoices.settings',
        'update' => 'invoices.settings',
        'destroy' => 'invoices.settings'
    ];


    protected $settingsBackRoute = 'invoices.invoices.index';

    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];

    protected $languageFile = 'invoices::invoices.status';

    protected $routes = [
        'index' => 'invoices.status.index',
        'create' => 'invoices.status.create',
        'show' => 'invoices.status.show',
        'edit' => 'invoices.status.edit',
        'store' => 'invoices.status.store',
        'destroy' => 'invoices.status.destroy',
        'update' => 'invoices.status.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
