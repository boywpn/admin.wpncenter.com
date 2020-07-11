<?php

namespace Modules\Accounts\Http\Controllers\Tabs;

use Modules\Accounts\Datatables\Tabs\AccountAssetsDatatable;
use Modules\Accounts\Datatables\Tabs\AccountTicketsDatatable;
use Modules\Accounts\Entities\Account;
use Modules\Assets\Entities\Asset;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;
use Modules\Tickets\Entities\Ticket;

class AccountsAssetsController extends ModuleCrudRelationController
{
    protected $datatable = AccountAssetsDatatable::class;

    protected $ownerModel = Account::class;

    protected $relationModel = Asset::class;

    protected $ownerModuleName = 'accounts';

    protected $relatedModuleName = 'assets';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'assets';

    protected $relationType = self::RT_ONE_TO_MANY;

    protected $belongsToName = 'account';

    protected $whereCondition = 'assets.id';
}
