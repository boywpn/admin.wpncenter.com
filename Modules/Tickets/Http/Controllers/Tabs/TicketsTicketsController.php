<?php

namespace Modules\Tickets\Http\Controllers\Tabs;

use Modules\Campaigns\Datatables\Tabs\CampaignsLeadDatatable;
use Modules\Campaigns\Entities\Campaign;
use Modules\Contacts\Datatables\Tabs\ContactCamapginsDatatable;
use Modules\Contacts\Datatables\Tabs\ContactDealsDatatable;
use Modules\Contacts\Datatables\Tabs\ContactTicketsDatatable;
use Modules\Contacts\Entities\Contact;
use Modules\Deals\Entities\Deal;
use Modules\Leads\Entities\Lead;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;
use Modules\Tickets\Datatables\Tabs\TicketsTicketsDatatable;
use Modules\Tickets\Entities\Ticket;

/**
 * Class TicketsTicketsController
 * @package Modules\Tickets\Http\Controllers\Tabs
 */
class TicketsTicketsController extends ModuleCrudRelationController
{
    protected $datatable = TicketsTicketsDatatable::class;

    protected $ownerModel = Ticket::class;

    protected $relationModel = Ticket::class;

    protected $ownerModuleName = 'tickets';

    protected $relatedModuleName = 'tickets';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'tickets';

    protected $relationType = self::RT_ONE_TO_MANY;

    protected $belongsToName = 'parent';

    protected $whereCondition = 'tickets.id';
}
