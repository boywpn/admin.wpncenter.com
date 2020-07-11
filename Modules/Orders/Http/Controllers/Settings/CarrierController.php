<?php

namespace Modules\Orders\Http\Controllers\Settings;

use Modules\Orders\Datatables\Settings\OrderCarrierDatatable;
use Modules\Orders\Entities\OrderCarrier;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class CarrierController extends ModuleCrudController
{
    protected $datatable = OrderCarrierDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = OrderCarrier::class;

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

    protected $languageFile = 'orders::orders.carrier';

    protected $routes = [
        'index' => 'orders.carrier.index',
        'create' => 'orders.carrier.create',
        'show' => 'orders.carrier.show',
        'edit' => 'orders.carrier.edit',
        'store' => 'orders.carrier.store',
        'destroy' => 'orders.carrier.destroy',
        'update' => 'orders.carrier.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
