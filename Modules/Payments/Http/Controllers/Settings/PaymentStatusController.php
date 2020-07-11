<?php

namespace Modules\Payments\Http\Controllers\Settings;

use Modules\Payments\Datatables\Settings\PaymentStatusDatatable;
use Modules\Payments\Entities\PaymentStatus;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class PaymentStatusController extends ModuleCrudController
{
    protected $datatable = PaymentStatusDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = PaymentStatus::class;

    protected $settingsBackRoute = 'payments.payments.index';

    protected $moduleName = 'payments';

    protected $permissions = [
        'browse' => 'payments.settings',
        'create' => 'payments.settings',
        'update' => 'payments.settings',
        'destroy' => 'payments.settings'
    ];

    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];

    protected $languageFile = 'payments::payments.status';

    protected $routes = [
        'index' => 'payments.status.index',
        'create' => 'payments.status.create',
        'show' => 'payments.status.show',
        'edit' => 'payments.status.edit',
        'store' => 'payments.status.store',
        'destroy' => 'payments.status.destroy',
        'update' => 'payments.status.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
