<?php

namespace Modules\Contacts\Http\Controllers\Tabs;

use Modules\Assets\Entities\Asset;
use Modules\Campaigns\Datatables\Tabs\CampaignsLeadDatatable;
use Modules\Campaigns\Entities\Campaign;
use Modules\Contacts\Datatables\Tabs\ContactAssetsDatatable;
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
 * Class ContactAssetsController
 * @package Modules\Contacts\Http\Controllers
 */
class ContactAssetsController extends ModuleCrudRelationController
{
    protected $datatable = ContactAssetsDatatable::class;

    protected $ownerModel = Contact::class;

    protected $relationModel = Asset::class;

    protected $ownerModuleName = 'contacts';

    protected $relatedModuleName = 'assets';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'assets';

    protected $relationType = self::RT_ONE_TO_MANY;

    protected $belongsToName = 'contact';

    protected $whereCondition = 'assets.contact_id';

    protected $whereType = self::WHERE_TYPE__COLUMN;
}
