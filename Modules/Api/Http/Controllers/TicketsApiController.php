<?php

namespace Modules\Api\Http\Controllers;


use Modules\Api\Http\Controllers\Base\CrudApiController;
use Modules\Api\Http\Requests\LeadApiRequest;
use Modules\Api\Http\Requests\TicketApiRequest;
use Modules\Leads\Entities\Lead;
use Modules\Tickets\Entities\Ticket;

/**
 * Ticket API Controller
 *
 * Class LeadsApiController
 *
 * @package Modules\Api\Http\Controllers
 */
class TicketsApiController extends CrudApiController
{

    protected $entityClass = Ticket::class;

    protected $moduleName = 'tickets';

    protected $languageFile = 'tickets::tickets';

    protected $with = [
      'ticketStatus'
    ];

    protected $permissions = [
        'browse' => 'tickets.browse',
        'create' => 'tickets.create',
        'update' => 'tickets.update',
        'destroy' => 'tickets.destroy'
    ];

    protected $showRoute = 'tickets.tickets.show';

    protected $storeRequest = TicketApiRequest::class;

    protected $updateRequest = TicketApiRequest::class;

    public function __construct()
    {
        parent::__construct();
    }

}