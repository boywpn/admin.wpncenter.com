<?php

namespace Modules\Products\Http\Controllers\Settings;

use Modules\Products\Datatables\Settings\ProductCategoryDatatable;
use Modules\Products\Entities\ProductCategory;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class CategoryController extends ModuleCrudController
{
    protected $datatable = ProductCategoryDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = ProductCategory::class;

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

    protected $languageFile = 'products::products.category';

    protected $routes = [
        'index' => 'products.category.index',
        'create' => 'products.category.create',
        'show' => 'products.category.show',
        'edit' => 'products.category.edit',
        'store' => 'products.category.store',
        'destroy' => 'products.category.destroy',
        'update' => 'products.category.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
