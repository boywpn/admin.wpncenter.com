<?php

namespace Modules\Calls\Http\Controllers\Settings;

use Modules\Calls\Datatables\Settings\DirectionTypeDatatable;
use Modules\Calls\Entities\DirectionType;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class DirectionTypeController extends ModuleCrudController
{

    protected $datatable = DirectionTypeDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = DirectionType::class;

    protected $moduleName = 'calls';

    protected $permissions = [
        'browse' => 'calls.settings',
        'create' => 'calls.settings',
        'update' => 'calls.settings',
        'destroy' => 'calls.settings'
    ];

    protected $settingsBackRoute = 'calls.calls.index';

    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];

    protected $languageFile = 'calls::calls.directiontype';

    protected $routes = [
        'index' => 'calls.directiontype.index',
        'create' => 'calls.directiontype.create',
        'show' => 'calls.directiontype.show',
        'edit' => 'calls.directiontype.edit',
        'store' => 'calls.directiontype.store',
        'destroy' => 'calls.directiontype.destroy',
        'update' => 'calls.directiontype.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }

}
