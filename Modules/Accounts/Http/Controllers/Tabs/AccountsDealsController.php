<?php

namespace Modules\Accounts\Http\Controllers\Tabs;

use Modules\Accounts\Datatables\Tabs\AccountDealsDatatable;
use Modules\Accounts\Entities\Account;
use Modules\Deals\Entities\Deal;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;

/**
 * Class AccountsDealsController
 * @package Modules\Accounts\Http\Controllers
 */
class AccountsDealsController extends ModuleCrudRelationController
{
    protected $datatable = AccountDealsDatatable::class;

    protected $ownerModel = Account::class;

    protected $relationModel = Deal::class;

    protected $ownerModuleName = 'accounts';

    protected $relatedModuleName = 'deals';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'deals';

    protected $relationType = self::RT_ONE_TO_MANY;

    protected $belongsToName = 'account';

    protected $whereCondition = 'deals.id';
}
