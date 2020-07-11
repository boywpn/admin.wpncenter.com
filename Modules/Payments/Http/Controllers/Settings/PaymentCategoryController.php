<?php

namespace Modules\Payments\Http\Controllers\Settings;

use Modules\Payments\Datatables\Settings\PaymentCategoryDatatable;
use Modules\Payments\Entities\PaymentCategory;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class PaymentCategoryController extends ModuleCrudController
{
    protected $datatable = PaymentCategoryDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = PaymentCategory::class;

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

    protected $languageFile = 'payments::payments.category';

    protected $routes = [
        'index' => 'payments.category.index',
        'create' => 'payments.category.create',
        'show' => 'payments.category.show',
        'edit' => 'payments.category.edit',
        'store' => 'payments.category.store',
        'destroy' => 'payments.category.destroy',
        'update' => 'payments.category.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
