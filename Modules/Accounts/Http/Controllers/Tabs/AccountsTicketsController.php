<?php

namespace Modules\Accounts\Http\Controllers\Tabs;

use Modules\Accounts\Datatables\Tabs\AccountTicketsDatatable;
use Modules\Accounts\Entities\Account;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;
use Modules\Tickets\Entities\Ticket;

/**
 * Class AccountsTicketsController
 * @package Modules\Contacts\Http\Controllers
 */
class AccountsTicketsController extends ModuleCrudRelationController
{
    protected $datatable = AccountTicketsDatatable::class;

    protected $ownerModel = Account::class;

    protected $relationModel = Ticket::class;

    protected $ownerModuleName = 'accounts';

    protected $relatedModuleName = 'tickets';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'tickets';

    protected $relationType = self::RT_ONE_TO_MANY;

    protected $belongsToName = 'account';

    protected $whereCondition = 'tickets.id';
}
