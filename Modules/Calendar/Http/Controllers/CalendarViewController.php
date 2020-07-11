<?php

namespace Modules\Calendar\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Calendar\Repository\EventRepository;
use Modules\Calendar\Service\CalendarService;
use Modules\Platform\Core\Http\Controllers\AppBaseController;

/**
 * Class CalendarViewController
 *
 * @package Modules\Calendar\Http\Controllers
 */
class CalendarViewController extends AppBaseController
{
    const ACTION_RESIZE = 'resize';
    const ACTION_DROP = 'drop';

    /**
     * @var CalendarService
     */
    private $calendarService;

    /**
     * @var EventRepository
     */
    private $eventRepo;

    /**
     * CalendarViewController constructor.
     *
     * @param CalendarService $calendarService
     */
    public function __construct(CalendarService $calendarService, EventRepository $eventRepo)
    {
        parent::__construct();
        $this->calendarService = $calendarService;
        $this->eventRepo = $eventRepo;
    }

    /**
     * Get accessible calendars for ajax
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxAccessibleCalendars()
    {
        $result = [
            'results' => $this->calendarService->getAccessibleCalendarsForAjax()
        ];

        return \Response::json($result);
    }

    /**
     * Get events for ajax request
     *
     * @param int $calendarId Calendar Identifier
     * @param Request $request request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxEvents($calendarId, Request $request)
    {
        $start = $request->get('start', Carbon::now()->startOfMonth());
        $end = $request->get('end', Carbon::now()->endOfMonth());

        $calendar = $this->calendarService->getCalendar($calendarId);

        // Check if this is default calendar
        $defaultCalendar = $this->calendarService->getOrCreateDefaultCalendar();

        if ($this->calendarService->calendarAccess($calendar)) {
            $calendarEvents = $this->calendarService->getCalendarEvents($calendarId, $start, $end);

            if ($calendar->id == $defaultCalendar->id) { // Add shared events
                $calendarEvents = collect($calendarEvents)->merge($this->calendarService->getSharedEvents($start, $end));
            }
        } else {
            $calendarEvents = [];
        }

        return \Response::json($calendarEvents);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function manageEvent(Request $request)
    {
        $eventId = $request->get('eventId');

        $action = $request->get('action');
        $startDate = $request->get('startDate');
        $endDate = $request->get('endDate');
        $fullDay = filter_var($request->get('fullDay',false),FILTER_VALIDATE_BOOLEAN);

        if($fullDay){
            $fullDay = 1;
        }else{
            $fullDay = 0;
        }

        $event = $this->eventRepo->findWithoutFail($eventId);

        $hasAccess = $this->calendarService->eventEditAccess($event);

        if (!$hasAccess) {
            return \Response::json(
                [
                    'eventId' => $event->id,
                    'action' => 'show_message',
                    'message' => trans('calendar::calendar.you_dont_have_access_to_event')
                ]
            );
        }

        if ($action == CalendarViewController::ACTION_RESIZE) {
            $event = $this->eventRepo->update(['end_date' => $endDate], $event->id);

            return \Response::json(
                [
                    'eventId' => $event->id,
                    'action' => 'refresh',
                    'message' => trans('calendar::calendar.event_resized')
                ]
            );
        }
        if ($action == CalendarViewController::ACTION_DROP) {
            $event = $this->eventRepo->update(['start_date' => $startDate, 'end_date' => $endDate, 'full_day' => $fullDay], $event->id);

            return \Response::json(
                [
                    'eventId' => $event->id,
                    'action' => 'refresh',
                    'message' => trans('calendar::calendar.event_moved')
                ]
            );
        }
    }

    /**
     * Load full calendar
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $view = view('calendar::calendar');

        $calendar = $this->calendarService->getCalendar($request->get('calid'));

        if ($calendar == null) {
            $calendar = $this->calendarService->getOrCreateDefaultCalendar();
        }

        $hasCalendarAccess = $this->calendarService->calendarAccess($calendar);

        if (!$hasCalendarAccess) {
            flash(trans('calendar::calendar.you_dont_have_access_to_calendar', ['id' => $calendar->id]))->error();
            return redirect(route('calendar.index'));
        }

        $fullCalendar = $this->calendarService->fullCalendarInstance($calendar);


        $view->with('accessibleCalendars', $this->calendarService->getAccessibleCalendars());
        $view->with('userCalendar', $calendar);
        $view->with('fullCalendar', $fullCalendar);

        return $view;
    }
}
