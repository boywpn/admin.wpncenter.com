<?php

namespace Modules\Products\Http\Controllers\Settings;

use Modules\Products\Datatables\Settings\ProductTypeDatatable;
use Modules\Products\Entities\ProductType;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class TypeController extends ModuleCrudController
{
    protected $datatable = ProductTypeDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = ProductType::class;

    protected $moduleName = 'products';

    protected $permissions = [
        'browse' => 'products.settings',
        'create' => 'products.settings',
        'update' => 'products.settings',
        'destroy' => 'products.settings'
    ];

    protected $settingsBackRoute = 'products.products.index';

    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];

    protected $languageFile = 'products::products.type';

    protected $routes = [
        'index' => 'products.type.index',
        'create' => 'products.type.create',
        'show' => 'products.type.show',
        'edit' => 'products.type.edit',
        'store' => 'products.type.store',
        'destroy' => 'products.type.destroy',
        'update' => 'products.type.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
