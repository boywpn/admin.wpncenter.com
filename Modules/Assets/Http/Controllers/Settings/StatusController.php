<?php

namespace Modules\Assets\Http\Controllers\Settings;

use Modules\Assets\Datatables\Settings\AssetStatusDatatable;
use Modules\Assets\Entities\AssetStatus;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class StatusController extends ModuleCrudController
{
    protected $datatable = AssetStatusDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = AssetStatus::class;

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

    protected $languageFile = 'assets::assets.status';

    protected $routes = [
        'index' => 'assets.status.index',
        'create' => 'assets.status.create',
        'show' => 'assets.status.show',
        'edit' => 'assets.status.edit',
        'store' => 'assets.status.store',
        'destroy' => 'assets.status.destroy',
        'update' => 'assets.status.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
