<?php

namespace Modules\Leads\Http\Controllers\Tabs;

use Modules\Leads\Datatables\Tabs\LeadProductsDatatable;
use Modules\Leads\Entities\Lead;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;
use Modules\Products\Entities\Product;

/**
 * Class LeadCampaignsController
 * @package Modules\Leads\Http\Controllers
 */
class LeadProductsController extends ModuleCrudRelationController
{
    protected $datatable = LeadProductsDatatable::class;

    protected $ownerModel = Lead::class;

    protected $relationModel = Product::class;

    protected $ownerModuleName = 'leads';

    protected $relatedModuleName = 'products';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'products';

    protected $whereCondition = 'products.id';
}
