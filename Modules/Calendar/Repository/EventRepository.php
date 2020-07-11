<?php

namespace Modules\Calendar\Repository;

use HipsterJazzbo\Landlord\Facades\Landlord;
use Modules\Calendar\Entities\Event;
use Modules\Platform\Core\Repositories\PlatformRepository;

/**
 * Class EventRepository
 * @package Modules\Calendar\Repository
 */
class EventRepository extends PlatformRepository
{
    public function model()
    {
        return Event::class;
    }

    public function getSharedEvents($start, $end)
    {
        $start = $start . ' 00:00:00';
        $end = $end . ' 23:59:29';

        $events = Event::where(function ($subquery) use ($start, $end) {
            $subquery->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end]);
            })->orWhere(function ($q) use ($start, $end) {
                $q->whereBetween('end_date', [$start, $end]);
            })->orWhere(function ($q) use ($start, $end) {
                $q->whereDate('start_date','<=',$start);
                $q->whereDate('end_date','>=',$end);
            });
        })->whereHas('sharedWith', function ($query) {
            $query->where('id', \Auth::user()->id);
        })->where('company_id','=',Landlord::getTenants()->first());

        return $events->get();
    }

    /**
     * Get Events for calendar
     *
     * @param $calendarId
     * @param $start
     * @param $end
     * @return mixed
     */
    public function getEvents($calendarId, $start, $end)
    {
        $start = $start . ' 00:00:00';
        $end = $end . ' 23:59:29';

        $events = Event::where('calendar_id', $calendarId)
            ->where(function ($subquery) use ($start, $end) {
                $subquery->where(function ($q) use ($start, $end) {
                    $q->whereBetween('start_date', [$start, $end]);
                })->orWhere(function ($q) use ($start, $end) {
                    $q->whereBetween('end_date', [$start, $end]);
                })->orWhere(function ($q) use ($start, $end) {
                    $q->whereDate('start_date','<=',$start);
                    $q->whereDate('end_date','>=',$end);
                });
            })->where('company_id','=',Landlord::getTenants()->first());


        return $events->get();
    }
}
