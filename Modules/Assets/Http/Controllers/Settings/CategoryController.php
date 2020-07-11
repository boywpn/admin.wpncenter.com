<?php

namespace Modules\Assets\Http\Controllers\Settings;

use Modules\Assets\Datatables\Settings\AssetCategoryDatatable;
use Modules\Assets\Entities\AssetCategory;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class CategoryController extends ModuleCrudController
{
    protected $datatable = AssetCategoryDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = AssetCategory::class;

    protected $moduleName = 'assets';

    protected $permissions = [
        'browse' => 'assets.settings',
        'create' => 'assets.settings',
        'update' => 'assets.settings',
        'destroy' => 'assets.settings'
    ];

    protected $settingsBackRoute = 'assets.assets.index';

    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];

    protected $languageFile = 'assets::assets.category';

    protected $routes = [
        'index' => 'assets.category.index',
        'create' => 'assets.category.create',
        'show' => 'assets.category.show',
        'edit' => 'assets.category.edit',
        'store' => 'assets.category.store',
        'destroy' => 'assets.category.destroy',
        'update' => 'assets.category.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
