<?php

namespace Modules\Leads\Http\Controllers\Tabs;

use Modules\Campaigns\Entities\Campaign;
use Modules\Leads\Datatables\Tabs\LeadCampaignsDatatable;
use Modules\Leads\Entities\Lead;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;

/**
 * Class LeadCampaignsController
 * @package Modules\Leads\Http\Controllers
 */
class LeadCampaignsController extends ModuleCrudRelationController
{
    protected $datatable = LeadCampaignsDatatable::class;

    protected $ownerModel = Lead::class;

    protected $relationModel = Campaign::class;

    protected $ownerModuleName = 'leads';

    protected $relatedModuleName = 'campaigns';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'campaigns';

    protected $whereCondition = 'campaigns.id';
}
