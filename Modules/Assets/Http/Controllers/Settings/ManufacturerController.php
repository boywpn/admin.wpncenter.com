<?php

namespace Modules\Assets\Http\Controllers\Settings;

use Modules\Assets\Datatables\Settings\AssetManufacturerDatatable;
use Modules\Assets\Entities\AssetManufacturer;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class ManufacturerController extends ModuleCrudController
{
    protected $datatable = AssetManufacturerDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = AssetManufacturer::class;

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

    protected $languageFile = 'assets::assets.manufacturer';

    protected $routes = [
        'index' => 'assets.manufacturer.index',
        'create' => 'assets.manufacturer.create',
        'show' => 'assets.manufacturer.show',
        'edit' => 'assets.manufacturer.edit',
        'store' => 'assets.manufacturer.store',
        'destroy' => 'assets.manufacturer.destroy',
        'update' => 'assets.manufacturer.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
