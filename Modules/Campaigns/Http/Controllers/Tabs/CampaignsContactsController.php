<?php

namespace Modules\Campaigns\Http\Controllers\Tabs;

use Modules\Campaigns\Datatables\Tabs\CampaignsContactsDatatable;
use Modules\Campaigns\Entities\Campaign;
use Modules\Contacts\Entities\Contact;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;

/**
 * Class CampaignsContactsController
 * @package Modules\Campaigns\Http\Controllers
 */
class CampaignsContactsController extends ModuleCrudRelationController
{
    protected $datatable = CampaignsContactsDatatable::class;

    protected $ownerModel = Campaign::class;

    protected $relationModel = Contact::class;

    protected $ownerModuleName = 'campaigns';

    protected $relatedModuleName = 'contacts';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'contacts';

    protected $whereCondition = 'contacts.id';
}
