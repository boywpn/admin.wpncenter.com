<?php

namespace Modules\Payments\Http\Controllers;

use Modules\Payments\Datatables\PaymentsDatatable;
use Modules\Payments\Entities\Payment;
use Modules\Payments\Http\Forms\PaymentForm;
use Modules\Payments\Http\Requests\PaymentRequest;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;

class PaymentsController extends ModuleCrudController
{
    protected $datatable = PaymentsDatatable::class;
    protected $formClass = PaymentForm::class;
    protected $storeRequest = PaymentRequest::class;
    protected $updateRequest = PaymentRequest::class;
    protected $entityClass = Payment::class;

    protected $moduleName = 'payments';

    protected $permissions = [
        'browse' => 'payments.browse',
        'create' => 'payments.create',
        'update' => 'payments.update',
        'destroy' => 'payments.destroy'
    ];

    protected $moduleSettingsLinks = [
        ['route' => 'payments.category.index', 'label' => 'settings.category'],
        ['route' => 'payments.status.index', 'label' => 'settings.status'],
        ['route' => 'payments.paymentmethod.index', 'label' => 'settings.paymentmethod'],
    ];

    protected $settingsPermission = 'payments.settings';

    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text', 'col-class' => 'col-lg-5'],
            'payment_date' => ['type' => 'date', 'col-class' => 'col-lg-5'],
            'income' => ['type' => 'checkbox', 'col-class' => 'col-lg-2'],
            'amount' => ['type' => 'text'],
            'payment_status_id' => ['type' => 'manyToOne', 'relation' => 'paymentStatus', 'column' => 'name'],
            'payment_category_id' => ['type' => 'manyToOne', 'relation' => 'paymentCategory', 'column' => 'name'],
            'payment_currency_id' => ['type' => 'manyToOne', 'relation' => 'paymentCurrency', 'column' => 'code','dont_translate' => true],
            'payment_payment_method_id' => [
                'type' => 'manyToOne',
                'relation' => 'paymentPaymentMethod',
                'column' => 'name',
            ],
            'owned_by' => ['type' => 'assigned_to'],
            'notes' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];

    protected $languageFile = 'payments::payments';

    protected $routes = [
        'index' => 'payments.payments.index',
        'create' => 'payments.payments.create',
        'show' => 'payments.payments.show',
        'edit' => 'payments.payments.edit',
        'store' => 'payments.payments.store',
        'destroy' => 'payments.payments.destroy',
        'update' => 'payments.payments.update',
        'import' => 'payments.payments.import',
        'import_process' =>  'payments.payments.import.process'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
