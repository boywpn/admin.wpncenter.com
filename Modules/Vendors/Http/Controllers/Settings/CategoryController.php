<?php

namespace Modules\Vendors\Http\Controllers\Settings;

use Modules\Vendors\Datatables\Settings\VendorCategoryDatatable;
use Modules\Vendors\Entities\VendorCategory;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class CategoryController extends ModuleCrudController
{
    protected $datatable = VendorCategoryDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = VendorCategory::class;

    protected $moduleName = 'vendors';

    protected $permissions = [
        'browse' => 'vendors.settings',
        'create' => 'vendors.settings',
        'update' => 'vendors.settings',
        'destroy' => 'vendors.settings'
    ];

    protected $settingsBackRoute = 'vendors.vendors.index';

    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];

    protected $languageFile = 'vendors::vendors.category';

    protected $routes = [
        'index' => 'vendors.category.index',
        'create' => 'vendors.category.create',
        'show' => 'vendors.category.show',
        'edit' => 'vendors.category.edit',
        'store' => 'vendors.category.store',
        'destroy' => 'vendors.category.destroy',
        'update' => 'vendors.category.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
