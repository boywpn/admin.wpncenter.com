<?php

namespace Modules\Accounts\Http\Controllers\Tabs;

use Modules\Accounts\Datatables\Tabs\AccountCallsDatatable;
use Modules\Accounts\Entities\Account;
use Modules\Calls\Entities\Call;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;

/**
 * Class AccountsTicketsController
 * @package Modules\Accounts\Http\Controllers\Tabs
 */
class AccountsCallsController extends ModuleCrudRelationController
{
    protected $datatable = AccountCallsDatatable::class;

    protected $ownerModel = Account::class;

    protected $relationModel = Call::class;

    protected $ownerModuleName = 'accounts';

    protected $relatedModuleName = 'calls';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'calls';

    protected $relationType = self::RT_ONE_TO_MANY;

    protected $belongsToName = 'account';

    protected $whereCondition = 'calls.id';
}
