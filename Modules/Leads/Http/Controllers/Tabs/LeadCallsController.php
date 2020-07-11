<?php

namespace Modules\Leads\Http\Controllers\Tabs;

use Modules\Calls\Entities\Call;
use Modules\Leads\Datatables\Tabs\LeadCallsDatatable;
use Modules\Leads\Entities\Lead;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;

/**
 * Class LeadCallsController
 * @package Modules\Contacts\Http\Controllers\Tabs
 */
class LeadCallsController extends ModuleCrudRelationController
{
    protected $datatable = LeadCallsDatatable::class;

    protected $ownerModel = Lead::class;

    protected $relationModel = Call::class;

    protected $ownerModuleName = 'leads';

    protected $relatedModuleName = 'calls';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'calls';

    protected $relationType = self::RT_ONE_TO_MANY;

    protected $belongsToName = 'lead';

    protected $whereCondition = 'calls.id';
}
