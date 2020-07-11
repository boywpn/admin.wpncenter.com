<?php

namespace Modules\Calendar\Repository;

use HipsterJazzbo\Landlord\Facades\Landlord;
use Modules\Calendar\Entities\Calendar;
use Modules\Platform\Core\Repositories\PlatformRepository;
use Modules\Platform\User\Entities\User;

/**
 * Class CalendarRepository
 * @package Modules\Calendar\Repository
 */
class CalendarRepository extends PlatformRepository
{
    public function model()
    {
        return Calendar::class;
    }

    /**
     * Get or create private calendar for user
     */
    public function getOrCreateDefaultCalendar()
    {
        $user = \Auth::user();

        $calendar = Calendar::where('owned_by_type', User::class)->where('owned_by_id', '=', $user->id)->first();

        if (empty($calendar)) {
            $calendar = new Calendar();
            $calendar->name = 'My Calendar';
            $calendar->is_public = false;
            $calendar->default_view = Calendar::WEEK_VIEW;
            $calendar->first_day = Calendar::WEEK_START_AT_MONDAY;
            $calendar->day_start_at = Calendar::DEFAULT_DAT_START_AT;
            $calendar->changeOwnerTo($user);
            $calendar->company_id = Landlord::getTenants()->first();

            $calendar->save();
        }

        return $calendar;
    }
}
