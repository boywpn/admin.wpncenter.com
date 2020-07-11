<?php

namespace Modules\Orders\Http\Controllers\Settings;

use Modules\Orders\Datatables\Settings\OrderStatusDatatable;
use Modules\Orders\Entities\OrderStatus;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class StatusController extends ModuleCrudController
{
    protected $datatable = OrderStatusDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = OrderStatus::class;

    protected $moduleName = 'orders';

    protected $permissions = [
        'browse' => 'orders.settings',
        'create' => 'orders.settings',
        'update' => 'orders.settings',
        'destroy' => 'orders.settings'
    ];


    protected $settingsBackRoute = 'orders.orders.index';

    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];

    protected $languageFile = 'orders::orders.status';

    protected $routes = [
        'index' => 'orders.status.index',
        'create' => 'orders.status.create',
        'show' => 'orders.status.show',
        'edit' => 'orders.status.edit',
        'store' => 'orders.status.store',
        'destroy' => 'orders.status.destroy',
        'update' => 'orders.status.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
