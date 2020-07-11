<?php

namespace Modules\Deals\Http\Controllers\Tabs;

use Modules\Contacts\Entities\Contact;
use Modules\Deals\Datatables\Tabs\DealsContactsDatatable;
use Modules\Deals\Entities\Deal;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;

/**
 * Class CampaignsContactsController
 * @package Modules\Campaigns\Http\Controllers
 */
class DealsContactsController extends ModuleCrudRelationController
{
    protected $datatable = DealsContactsDatatable::class;

    protected $ownerModel = Deal::class;

    protected $relationModel = Contact::class;

    protected $ownerModuleName = 'deals';

    protected $relatedModuleName = 'contacts';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'contacts';

    protected $whereCondition = 'contacts.id';
}
