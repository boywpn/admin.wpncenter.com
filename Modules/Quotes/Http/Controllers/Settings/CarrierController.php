<?php

namespace Modules\Quotes\Http\Controllers\Settings;

use Modules\Quotes\Datatables\Settings\QuoteCarrierDatatable;
use Modules\Quotes\Entities\QuoteCarrier;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class CarrierController extends ModuleCrudController
{
    protected $datatable = QuoteCarrierDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = QuoteCarrier::class;

    protected $moduleName = 'quotes';

    protected $permissions = [
        'browse' => 'quotes.settings',
        'create' => 'quotes.settings',
        'update' => 'quotes.settings',
        'destroy' => 'quotes.settings'
    ];

    protected $settingsBackRoute = 'quotes.quotes.index';

    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];

    protected $languageFile = 'quotes::quotes.carrier';

    protected $routes = [
        'index' => 'quotes.carrier.index',
        'create' => 'quotes.carrier.create',
        'show' => 'quotes.carrier.show',
        'edit' => 'quotes.carrier.edit',
        'store' => 'quotes.carrier.store',
        'destroy' => 'quotes.carrier.destroy',
        'update' => 'quotes.carrier.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
