<?php

namespace Modules\Deals\Http\Controllers\Settings;

use Modules\Deals\Datatables\Settings\DealStageDatatable;
use Modules\Deals\Entities\DealStage;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class StageController extends ModuleCrudController
{
    protected $datatable = DealStageDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = DealStage::class;

    protected $moduleName = 'deals';

    protected $permissions = [
        'browse' => 'deals.settings',
        'create' => 'deals.settings',
        'update' => 'deals.settings',
        'destroy' => 'deals.settings'
    ];

    protected $settingsBackRoute = 'deals.deals.index';

    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];

    protected $languageFile = 'deals::deals.stage';

    protected $routes = [
        'index' => 'deals.stage.index',
        'create' => 'deals.stage.create',
        'show' => 'deals.stage.show',
        'edit' => 'deals.stage.edit',
        'store' => 'deals.stage.store',
        'destroy' => 'deals.stage.destroy',
        'update' => 'deals.stage.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
