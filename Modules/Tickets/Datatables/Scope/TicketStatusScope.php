<?php

namespace Modules\Tickets\Datatables\Scope;

use Yajra\DataTables\Contracts\DataTableScope;

/**
 * Class TicketStatusScope
 * @package Modules\Tickets\Datatables\Scope
 */
class TicketStatusScope implements DataTableScope
{
    private $ticketStatus;

    public function __construct($ticketStatus)
    {
        $this->ticketStatus = $ticketStatus;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query
     */
    public function apply($query)
    {
        $query->where('ticket_status_id', $this->ticketStatus);
    }
}
