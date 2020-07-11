<?php

namespace Modules\Calendar\Http\Controllers\Settings;

use Modules\Calendar\Datatables\Settings\EventStatusDatatable;
use Modules\Calendar\Entities\EventStatus;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class EventStatusController extends ModuleCrudController
{
    protected $datatable = EventStatusDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = EventStatus::class;

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

    protected $languageFile = 'calendar::calendar.status';

    protected $routes = [
        'index' => 'calendar.status.index',
        'create' => 'calendar.status.create',
        'show' => 'calendar.status.show',
        'edit' => 'calendar.status.edit',
        'store' => 'calendar.status.store',
        'destroy' => 'calendar.status.destroy',
        'update' => 'calendar.status.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
