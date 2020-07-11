<?php

namespace Modules\Contacts\Http\Controllers\Tabs;

use Modules\Campaigns\Datatables\Tabs\CampaignsLeadDatatable;
use Modules\Campaigns\Entities\Campaign;
use Modules\Contacts\Datatables\Tabs\ContactCamapginsDatatable;
use Modules\Contacts\Datatables\Tabs\ContactDealsDatatable;
use Modules\Contacts\Entities\Contact;
use Modules\Deals\Entities\Deal;
use Modules\Leads\Entities\Lead;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;

/**
 * Class ContactDealsController
 * @package Modules\Contacts\Http\Controllers
 */
class ContactDealsController extends ModuleCrudRelationController
{
    protected $datatable = ContactDealsDatatable::class;

    protected $ownerModel = Contact::class;

    protected $relationModel = Deal::class;

    protected $ownerModuleName = 'contacts';

    protected $relatedModuleName = 'deals';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'deals';

    protected $whereCondition = 'deals.id';
}
