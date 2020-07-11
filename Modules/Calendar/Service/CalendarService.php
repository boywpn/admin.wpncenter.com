<?php

namespace Modules\Calendar\Service;

use Carbon\Carbon;
use HipsterJazzbo\Landlord\Facades\Landlord;
use Modules\Calendar\Edofre\Fullcalendar;
use Modules\Calendar\Edofre\JsExpression;
use Modules\Calendar\Entities\Calendar;
use Modules\Calendar\Entities\Event;
use Modules\Calendar\Repository\CalendarRepository;
use Modules\Calendar\Repository\EventRepository;
use Modules\Platform\Core\Helper\UserHelper;
use Modules\Platform\User\Entities\User;

/**
 * Class CalendarService
 * @package Modules\Calendar\Service
 */
class CalendarService
{
    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * @var CalendarRepository
     */
    private $calendarRepository;

    /**
     * CalendarService constructor.
     * @param CalendarRepository $calendarRepository
     * @param EventRepository $eventRepository
     */
    public function __construct(CalendarRepository $calendarRepository, EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
        $this->calendarRepository = $calendarRepository;
    }

    /**
     * @return \Modules\Calendar\Entities\Calendar
     */
    public function getOrCreateDefaultCalendar()
    {
        return $this->calendarRepository->getOrCreateDefaultCalendar();
    }

    /**
     * Get fullcalendar instance
     * @param Calendar|null $calendar
     * @param array $customSettings
     * @return Fullcalendar
     */
    public function fullCalendarInstance(Calendar $calendar = null, $customSettings = [])
    {
        $user = \Auth::user();

        if (empty($calendar)) {
            $calendar = $this->getOrCreateDefaultCalendar();
        }

        $fullCalendar = new Fullcalendar();

        $fullCalendar->setEvents(route('calendar.events', ['calendarId' => $calendar->id]));

        $settings =  [
            'locale' => $user->language != null ? $user->language->language_key : 'en',
            'selectable' => true,
            'height' => 'auto',
            'defaultView' => $calendar->default_view,
            'firstDay' => $calendar->first_day,
            'minTime' => $calendar->day_start_at,
            'timeFormat' => UserHelper::userJsTimeFormat() == 'HH:mm' ? 'HH:mm' : 'h:mm A',
            'slotLabelFormat' => UserHelper::userJsTimeFormat() == 'HH:mm' ? 'HH:mm' : 'h:mm A',
            'slotDuration' => config('calendar.slotDuration'),
            'snapDuration' => config('calendar.snapDuration'),

            'eventDrop' => new JsExpression('function(event,delta,revertFunc){
                    BAP_Calendar.calendarEventDrop(event,delta,revertFunc);
                }'),
            'eventClick' => new JsExpression('function(calEvent,jsEvent,view){
                     BAP_Calendar.calendarEventClick(calEvent,jsEvent,view);
                }'),
            'eventResize' => new JsExpression('function(event,delta,revertFunction){
                    BAP_Calendar.calendarEventResize(event,delta,revertFunction);
                }')

        ];

        $settings = array_replace($settings, $customSettings);


        $fullCalendar->setOptions(
           $settings
        );

        return  $fullCalendar;
    }

    /**
     * Check if user can edit calendar
     *
     * @param Calendar $calendar
     * @return bool
     */
    public function calendarEditAccess(Calendar $calendar)
    {
        $result = false;

        $user = \Auth::user();

        if ($user->access_to_all_entity) {
            return true;
        }
        if (!$calendar->hasOwner()) {
            return true;
        }
        if ($calendar->isOwnedBy($user)) {
            return true;
        }
        if($calendar->company_id == Landlord::getTenants()->first()){
            return true;
        }

        return $result;
    }

    /**
     * Check calendar access
     *
     * @param Calendar $calendar
     * @return bool
     */
    public function calendarAccess(Calendar $calendar)
    {
        $result = false;

        $user = \Auth::user();

        if ($user->access_to_all_entity) {
            return true;
        }
        if ($calendar->is_public) {
            return true;
        }
        if (!$calendar->hasOwner()) {
            return true;
        }
        if ($calendar->isOwnedBy($user)) {
            return true;
        }
        if($calendar->company_id == Landlord::getTenants()->first()){
            return true;
        }

        return $result;
    }

    /**
     * Check if user has access to edit event entity
     *
     * @param Event $event
     * @return bool
     */
    public function eventEditAccess(Event $event)
    {
        $result = false;

        $user = \Auth::user();

        if ($user->access_to_all_entity) {
            return true;
        }
        if ($event->isOwnedBy($user)) {
            return true;
        }
        if ($event->created_by == \Auth::user()->id) {
            return true;
        }
        if($event->company_id == Landlord::getTenants()->first()){
            return true;
        }


        return $result;
    }

    /**
     * Get Calendar
     * @param $calendarId
     * @return null
     */
    public function getCalendar($calendarId)
    {

        $calendar =  $this->calendarRepository->findWithoutFail($calendarId);


        if(!empty($calendar)) {
            if ($calendar->company_id == Landlord::getTenants()->first() || $calendar->company_id == null ) {
                return $calendar;
            }
        }
        return null;
    }

    /**
     * Get accessible calendars for ajax
     * @return array
     */
    public function getAccessibleCalendarsForAjax()
    {
        $calendars = $this->getAccessibleCalendars();

        $calendarResult = [];

        foreach ($calendars as $group => $values) {
            $tempCalendar = [];
            foreach ($values as $id => $name) {
                $tempCalendar[] = [
                    'id' => $id,
                    'text' => $name
                ];
            }
            $calendarResult[] = [
                'text' => $group,
                'children' => $tempCalendar
            ];
        }

        return $calendarResult;
    }

    /**
     * @return array
     */
    public function getAccessibleCalendars()
    {

        $publicCalendars = $this->calendarRepository->orderBy('name', 'asc')->findWhere([
            'is_public' => true,
            'company_id' => Landlord::getTenants()->first()
        ])->pluck('name', 'id')->toArray();

        $privateCalendars = $this->calendarRepository->orderBy('name', 'asc')->findWhere([
            'is_public' => false,
            'owned_by_id' => \Auth::user()->id,
            'owned_by_type' => User::class
        ])->pluck('name', 'id')->toArray();

        $options = [
            trans('core::core.form.optgroup.private_calendars') => $privateCalendars,
            trans('core::core.form.optgroup.public_calendars') => $publicCalendars
        ];

        return $options;
    }


    public function getSharedEvents($start, $end)
    {
        $user = \Auth::user();

        $events = $this->eventRepository->getSharedEvents($start, $end);

        $feedEvents = [];
        foreach ($events as $event) {
            $feedEvents[] = new \Modules\Calendar\Edofre\Event([
                'id' => $event->id,
                'title' => $event->name.' ('.trans('calendar::calendar.shared').')',
                'allDay' => $event->full_day == 1 ? true : false,
                'start' => $event->start_date->setTimezone($user->time_zone),
                'end' => $event->end_date->setTimezone($user->time_zone),
                'editable' => true,
                'startEditable' => true,
                'durationEditable' => true,
                'backgroundColor' => $event->event_color,
                'borderColor' => '#cccccc',
                'textColor' => '#00000',
            ]);
        }

        return $feedEvents;
    }

    /**
     * Get transformed calendar events
     *
     * @param $calendarId
     * @param $start
     * @param $end
     * @return array
     */
    public function getCalendarEvents($calendarId, $start, $end)
    {
        $user = \Auth::user();

        $events = $this->eventRepository->getEvents($calendarId, $start, $end);

        $feedEvents = [];
        foreach ($events as $event) {
            $feedEvents[] = new \Modules\Calendar\Edofre\Event([
                'id' => $event->id,
                'title' => $event->name,
                'allDay' => $event->full_day == 1 ? true : false,
                'start' =>  $event->full_day == 1 ?  $event->start_date->setTimezone($user->time_zone)->hour(0)->minute(0)->second(0) : $event->start_date->setTimezone($user->time_zone),
                'end' =>    $event->full_day == 1 ? $event->end_date->setTimezone($user->time_zone)->hour(0)->minute(0)->second(0) : $event->end_date->setTimezone($user->time_zone),
                'editable' => true,
                'startEditable' => true,
                'durationEditable' => true,
                'backgroundColor' => $event->event_color,
                'borderColor' => '#cccccc',
                'textColor' => '#00000',
            ]);
        }


        return $feedEvents;
    }
}
