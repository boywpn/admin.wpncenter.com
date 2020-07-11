<?php

namespace Modules\Calendar\Events;

use Modules\Calendar\Entities\Calendar;

/**
 * Class CalendarsEvent
 * @package Modules\Calendar\Events
 */
class CalendarsEvent
{


    /**
     * @param Calendar $calendar
     */
    public function creating(Calendar $calendar)
    {
        if(\Auth::user() != null ) {
            $calendar->created_by = \Auth::user()->id;

            if ($calendar->is_public == 0) {
                $calendar->changeOwnerTo(\Auth::user());
            }
        }
    }
}
