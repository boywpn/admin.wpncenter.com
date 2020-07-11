<?php

namespace Modules\Calendar\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Calendar\Datatables\EventDatatable;
use Modules\Calendar\Entities\Event;
use Modules\Calendar\Http\Forms\EventForm;
use Modules\Calendar\Http\Requests\EventRequest;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;

/**
 * Class EventsController
 * @package Modules\Calendar\Http\Controllers
 */
class EventsController extends ModuleCrudController
{
    protected $datatable = EventDatatable::class;

    protected $formClass = EventForm::class;

    protected $formModalCssClass = 'module_form eventModalForm';

    protected $storeRequest = EventRequest::class;

    protected $updateRequest = EventRequest::class;

    protected $entityClass = Event::class;

    protected $moduleName = 'calendar';

    protected $permissions = [
        'browse' => 'event.browse',
        'create' => 'event.create',
        'update' => 'event.update',
        'destroy' => 'event.destroy',
    ];

    protected $moduleSettingsLinks = [
        ['route' => 'calendar.priority.index', 'label' => 'settings.priority'],
        ['route' => 'calendar.status.index', 'label' => 'settings.status'],
    ];

    protected $routes = [
        'index' => 'calendar.events.index',
        'create' => 'calendar.events.create',
        'show' => 'calendar.events.show',
        'edit' => 'calendar.events.edit',
        'store' => 'calendar.events.store',
        'destroy' => 'calendar.events.destroy',
        'update' => 'calendar.events.update'
    ];

    public function index(Request $request)
    {
        return redirect()->route('calendar.index');
    }

    protected $settingsPermission = 'calendar.settings';

    protected $showFields = [

        'information' => [
            'name' => [
                'type' => 'text',
                'col-class' => 'col-lg-12'
            ],
            'start_date' => [
                'type' => 'datetime',
            ],

            'end_date' => [
                'type' => 'datetime',
            ],
            'sharedWith' => ['type' => 'oneToMany', 'relation' => 'sharedWith', 'column' => 'name'],
            'full_day' => [
                'type' => 'checkbox',
            ],
            'event_color' => [
                'type' => 'text',
            ],

            'event_priority_id' => [
                'type' => 'manyToOne',
                'relation' => 'eventPriority',
                'column' => 'name'
            ],

            'event_status_id' => [
                'type' => 'manyToOne',
                'relation' => 'eventStatus',
                'column' => 'name'
            ],

        ],

        'description' => [
            'description' => [
                'type' => 'text',
                'col-class' => 'col-lg-12'
            ],
        ]
    ];

    protected $languageFile = 'calendar::events';

    public function __construct()
    {
        parent::__construct();
    }
}
