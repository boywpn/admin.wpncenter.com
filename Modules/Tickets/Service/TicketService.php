<?php

namespace Modules\Tickets\Service;

use Carbon\Carbon;
use HipsterJazzbo\Landlord\Facades\Landlord;

/**
 *
 * Class TicketService
 *
 * @package Modules\Tickets\Service
 */
class TicketService
{

    public function groupByStatus()
    {
        return $this->groupBy('tickets_dict_status', 'ticket_status_id');
    }

    public function groupByPriority()
    {
        return $this->groupBy('tickets_dict_priority','ticket_priority_id');
    }

    private function groupBy($table = 'tickets_dict_status', $fk = 'ticket_status_id')
    {

        $result = \DB::table('tickets')
            ->select($table . '.name', \DB::raw('count(1) as counter'))
            ->join($table, 'tickets.' . $fk, '=', $table . '.id')
            ->groupBy($table . '.name')
            ->whereMonth('tickets.created_at', '=', Carbon::today()->month);
        if (Landlord::hasTenant('company_id')) {
            $result->where('tickets.company_id', Landlord::getTenantId('company_id'));
        }

        $result = $result->orderBy('counter', 'asc')->get()->toArray();

        return $result;
    }


}