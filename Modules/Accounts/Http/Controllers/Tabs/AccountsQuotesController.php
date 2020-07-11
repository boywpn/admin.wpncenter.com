<?php

namespace Modules\Accounts\Http\Controllers\Tabs;

use Modules\Accounts\Datatables\Tabs\AccountDealsDatatable;
use Modules\Accounts\Datatables\Tabs\AccountQuotesDatatable;
use Modules\Accounts\Entities\Account;
use Modules\Deals\Entities\Deal;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;
use Modules\Quotes\Entities\Quote;

/**
 * Class AccountsQuotesController
 * @package Modules\Accounts\Http\Controllers
 */
class AccountsQuotesController extends ModuleCrudRelationController
{
    protected $datatable = AccountQuotesDatatable::class;

    protected $ownerModel = Account::class;

    protected $relationModel = Quote::class;

    protected $ownerModuleName = 'accounts';

    protected $relatedModuleName = 'quotes';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'quotes';

    protected $relationType = self::RT_ONE_TO_MANY;

    protected $belongsToName = 'account';

    protected $whereCondition = 'quotes.id';
}
