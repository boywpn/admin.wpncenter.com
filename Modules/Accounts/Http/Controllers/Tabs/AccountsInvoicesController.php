<?php

namespace Modules\Accounts\Http\Controllers\Tabs;

use Modules\Accounts\Datatables\Tabs\AccountDealsDatatable;
use Modules\Accounts\Datatables\Tabs\AccountInvoicesDatatable;
use Modules\Accounts\Datatables\Tabs\AccountOrdersDatatable;
use Modules\Accounts\Datatables\Tabs\AccountQuotesDatatable;
use Modules\Accounts\Entities\Account;
use Modules\Deals\Entities\Deal;
use Modules\Invoices\Entities\Invoice;
use Modules\Orders\Entities\Order;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;
use Modules\Quotes\Entities\Quote;

/**
 * Class AccountsOrdersController
 * @package Modules\Accounts\Http\Controllers
 */
class AccountsInvoicesController extends ModuleCrudRelationController
{
    protected $datatable = AccountInvoicesDatatable::class;

    protected $ownerModel = Account::class;

    protected $relationModel = Invoice::class;

    protected $ownerModuleName = 'accounts';

    protected $relatedModuleName = 'invoices';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'invoices';

    protected $relationType = self::RT_ONE_TO_MANY;

    protected $belongsToName = 'account';

    protected $whereCondition = 'invoices.id';
}
