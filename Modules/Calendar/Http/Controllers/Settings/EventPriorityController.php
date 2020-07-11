<?php

namespace Modules\Calendar\Http\Controllers\Settings;

use Modules\Calendar\Datatables\Settings\EventPriorityDatatable;
use Modules\Calendar\Entities\EventPriority;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class EventPriorityController extends ModuleCrudController
{
    protected $datatable = EventPriorityDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = EventPriority::class;

    protected $moduleName = 'calendar';

    protected $permissions = [
        'browse' => 'calendar.settings',
        'create' => 'calendar.settings',
        'update' => 'calendar.settings',
        'destroy' => 'calendar.settings'
    ];

    protected $settingsBackRoute = 'calendar.index';

    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];

    protected $languageFile = 'calendar::calendar.priority';

    protected $routes = [
        'index' => 'calendar.priority.index',
        'create' => 'calendar.priority.create',
        'show' => 'calendar.priority.show',
        'edit' => 'calendar.priority.edit',
        'store' => 'calendar.priority.store',
        'destroy' => 'calendar.priority.destroy',
        'update' => 'calendar.priority.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
