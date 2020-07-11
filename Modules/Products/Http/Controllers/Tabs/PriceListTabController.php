<?php

namespace Modules\Products\Http\Controllers\Tabs;

use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;
use Modules\Products\Datatables\Tabs\PriceListDatatable;
use Modules\Products\Entities\PriceList;
use Modules\Products\Entities\Product;

/**
 * Class PriceListTabController
 * @package Modules\Products\Http\Controllers\Tabs
 */
class PriceListTabController extends ModuleCrudRelationController
{
    protected $datatable = PriceListDatatable::class;

    protected $ownerModel = Product::class;

    protected $relationModel = PriceList::class;

    protected $ownerModuleName = 'products';

    protected $relatedModuleName = 'products';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'priceList';

    protected $relationType = self::RT_ONE_TO_MANY;

    protected $belongsToName = 'product';

    protected $whereCondition = 'price_list.product_id';

    protected $whereType = self::WHERE_TYPE__COLUMN;
}
