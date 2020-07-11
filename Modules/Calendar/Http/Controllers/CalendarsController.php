<?php

namespace Modules\Calendar\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Calendar\Datatables\CalendarDatatable;
use Modules\Calendar\Entities\Calendar;
use Modules\Calendar\Http\Forms\CalendarForm;
use Modules\Calendar\Http\Requests\CalendarRequest;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;

/**
 * Class CalendarsController
 * @package Modules\Calendar\Http\Controllers
 */
class CalendarsController extends ModuleCrudController
{
    protected $datatable = CalendarDatatable::class;

    protected $formClass = CalendarForm::class;

    protected $storeRequest = CalendarRequest::class;

    protected $updateRequest = CalendarRequest::class;

    protected $entityClass = Calendar::class;

    protected $formModalCssClass = "module_form calendarModalForm";

    protected $moduleName = 'calendar';

    protected $disableNextPrev = true;
    protected $permissions = [
        'browse' => 'calendar.browse',
        'create' => 'calendar.create',
        'update' => 'calendar.update',
        'destroy' => 'calendar.destroy',
    ];

    // permissions...
    protected $moduleSettingsLinks = [

    ];
    protected $settingsPermission = 'calendar.settings';
    protected $showFields = [
        'information' => [
            'name' => [
                'type' => 'text',
                'col-class' => 'col-lg-10'
            ],
            'is_public' => [
                'type' => 'checkbox',
                'col-class' => 'col-lg-2'
            ],
            'first_day' => [
                'type' => 'checkbox',
                'col-class' => 'col-lg-4'
            ],

            'default_view' => [
                'type' => 'text',
                'col-class' => 'col-lg-4'
            ],
            'day_start_at' => [
                'type' => 'text',
                'col-class' => 'col-lg-4'
            ],
        ],
    ];
    protected $routes = [
        'index' => 'calendar.calendars.index',
        'create' => 'calendar.calendars.create',
        'show' => 'calendar.calendars.show',
        'edit' => 'calendar.calendars.edit',
        'store' => 'calendar.calendars.store',
        'destroy' => 'calendar.calendars.destroy',
        'update' => 'calendar.calendars.update'
    ];
    protected $languageFile = 'calendar::calendar';

    public function __construct()
    {
        parent::__construct();
    }

    public function edit($identifier)
    {
        $user = \Auth::user();

        $repository = $this->getRepository();

        $entity = $repository->find($identifier);

        if (!$user->access_to_all_entity) {
            if ($entity->is_public) { //Overrite to allow edit public calendar only by creator
                if ($entity->created_by != \Auth::user()->id) {
                    flash(trans('calendar::calendar.allowed_only_by_creator'))->error();
                    return redirect(route('calendar.index'));
                }
            }
        }

        return parent::edit($identifier);
    }

    /**
     * Update entity
     *
     * @param $identifier
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($identifier)
    {
        if ($this->permissions['update'] != '' && !\Auth::user()->hasPermissionTo($this->permissions['update'])) {
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        }

        $repository = $this->getRepository();

        $entity = $repository->find($identifier);

        $this->entity = $entity;

        if (empty($entity)) {
            flash(trans('core::core.entity.entity_not_found'))->error();

            return redirect(route($this->routes['index']));
        }

        if ($this->blockEntityOwnableAccess()) {
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        }

        $input = $this->form($this->formClass)->getFieldValues(true);

        $repository = $this->getRepository();

        $entity = $repository->updateEntity($input, $entity);

        $this->entity = $entity;

        flash(trans('core::core.entity.updated'))->success();

        return redirect(route($this->routes['show'], $entity));
    }

    public function index(Request $request)
    {
        return redirect()->route('calendar.index');
    }
}
