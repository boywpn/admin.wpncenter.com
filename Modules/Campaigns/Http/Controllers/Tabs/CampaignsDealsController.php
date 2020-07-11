<?php

namespace Modules\Campaigns\Http\Controllers\Tabs;

use Modules\Campaigns\Datatables\Tabs\CampaignsContactsDatatable;
use Modules\Campaigns\Datatables\Tabs\CampaignsDealsDatatable;
use Modules\Campaigns\Entities\Campaign;
use Modules\Contacts\Entities\Contact;
use Modules\Deals\Entities\Deal;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;

/**
 * Class CampaignsDealsController
 * @package Modules\Campaigns\Http\Controllers
 */
class CampaignsDealsController extends ModuleCrudRelationController
{
    protected $datatable = CampaignsDealsDatatable::class;

    protected $ownerModel = Campaign::class;

    protected $relationModel = Deal::class;

    protected $ownerModuleName = 'campaigns';

    protected $relatedModuleName = 'deals';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'deals';

    protected $whereCondition = 'deals.id';
}
