<?php

namespace Modules\Accounts\Http\Controllers\Tabs;

use Modules\Accounts\Datatables\Tabs\AccountDealsDatatable;
use Modules\Accounts\Datatables\Tabs\AccountOrdersDatatable;
use Modules\Accounts\Datatables\Tabs\AccountQuotesDatatable;
use Modules\Accounts\Entities\Account;
use Modules\Deals\Entities\Deal;
use Modules\Orders\Entities\Order;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;
use Modules\Quotes\Entities\Quote;

/**
 * Class AccountsOrdersController
 * @package Modules\Accounts\Http\Controllers
 */
class AccountsOrdersController extends ModuleCrudRelationController
{
    protected $datatable = AccountOrdersDatatable::class;

    protected $ownerModel = Account::class;

    protected $relationModel = Order::class;

    protected $ownerModuleName = 'accounts';

    protected $relatedModuleName = 'orders';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'orders';

    protected $relationType = self::RT_ONE_TO_MANY;

    protected $belongsToName = 'account';

    protected $whereCondition = 'orders.id';
}
