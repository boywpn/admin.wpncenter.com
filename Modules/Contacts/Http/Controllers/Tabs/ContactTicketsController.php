<?php

namespace Modules\Contacts\Http\Controllers\Tabs;

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
use Modules\Tickets\Entities\Ticket;

/**
 * Class ContactTicketsController
 * @package Modules\Contacts\Http\Controllers
 */
class ContactTicketsController extends ModuleCrudRelationController
{
    protected $datatable = ContactTicketsDatatable::class;

    protected $ownerModel = Contact::class;

    protected $relationModel = Ticket::class;

    protected $ownerModuleName = 'contacts';

    protected $relatedModuleName = 'tickets';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'tickets';

    protected $relationType = self::RT_ONE_TO_MANY;

    protected $belongsToName = 'contact';

    protected $whereCondition = 'tickets.contact_id';

    protected $whereType = self::WHERE_TYPE__COLUMN;
}
