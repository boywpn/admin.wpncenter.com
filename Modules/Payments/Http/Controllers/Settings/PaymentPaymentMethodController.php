<?php

namespace Modules\Payments\Http\Controllers\Settings;

use Modules\Payments\Datatables\Settings\PaymentPaymentMethodDatatable;
use Modules\Payments\Entities\PaymentPaymentMethod;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class PaymentPaymentMethodController extends ModuleCrudController
{
    protected $datatable = PaymentPaymentMethodDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = PaymentPaymentMethod::class;

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

    protected $languageFile = 'payments::payments.paymentmethod';

    protected $routes = [
        'index' => 'payments.paymentmethod.index',
        'create' => 'payments.paymentmethod.create',
        'show' => 'payments.paymentmethod.show',
        'edit' => 'payments.paymentmethod.edit',
        'store' => 'payments.paymentmethod.store',
        'destroy' => 'payments.paymentmethod.destroy',
        'update' => 'payments.paymentmethod.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
