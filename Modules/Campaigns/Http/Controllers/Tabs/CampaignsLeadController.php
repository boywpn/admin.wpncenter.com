<?php

namespace Modules\Campaigns\Http\Controllers\Tabs;

use Modules\Campaigns\Datatables\Tabs\CampaignsLeadDatatable;
use Modules\Campaigns\Entities\Campaign;
use Modules\Leads\Entities\Lead;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;

/**
 * Class CampaignsLeadController
 * @package Modules\Campaigns\Http\Controllers
 */
class CampaignsLeadController extends ModuleCrudRelationController
{
    protected $datatable = CampaignsLeadDatatable::class;

    protected $ownerModel = Campaign::class;

    protected $relationModel = Lead::class;

    protected $ownerModuleName = 'campaigns';

    protected $relatedModuleName = 'leads';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'leads';

    protected $whereCondition = 'leads.id';
}
