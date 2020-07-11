<?php

namespace Modules\Calendar\Events;

use Modules\Calendar\Entities\Event;
use Modules\Calendar\Service\CalendarService;

/**
 * Calendar event creating event
 *
 * Class CalendarEventEvent
 * @package Modules\Calendar\Events
 */
class EventsEvent
{


    /**
     * Assign event to user is its private calendar
     *
     * @param Event $event
     */
    public function creating(Event $event)
    {
        $auth = \Auth::user();
        if($auth != null ) {
            $event->created_by = \Auth::user()->id;

            if (empty($event->calendar)) {
                $defaultCalendar = \App::make(CalendarService::class)->getOrCreateDefaultCalendar();

                $event->calendar()->associate($defaultCalendar);
            }

            if ($event->calendar->is_public == 0) {
                $event->changeOwnerTo(\Auth::user());
            }
        }
    }
}
