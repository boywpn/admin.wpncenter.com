<?php

namespace Modules\Quotes\Http\Controllers\Settings;

use Modules\Quotes\Datatables\Settings\QuoteStageDatatable;
use Modules\Quotes\Entities\QuoteStage;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class StageController extends ModuleCrudController
{
    protected $datatable = QuoteStageDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = QuoteStage::class;

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

    protected $languageFile = 'quotes::quotes.stage';

    protected $routes = [
        'index' => 'quotes.stage.index',
        'create' => 'quotes.stage.create',
        'show' => 'quotes.stage.show',
        'edit' => 'quotes.stage.edit',
        'store' => 'quotes.stage.store',
        'destroy' => 'quotes.stage.destroy',
        'update' => 'quotes.stage.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
