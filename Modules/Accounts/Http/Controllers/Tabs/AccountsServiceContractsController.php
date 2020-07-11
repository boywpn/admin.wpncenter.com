<?php

namespace Modules\Accounts\Http\Controllers\Tabs;

use Modules\Accounts\Datatables\Tabs\AccountServiceContractsDatatable;
use Modules\Accounts\Datatables\Tabs\AccountTicketsDatatable;
use Modules\Accounts\Entities\Account;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;
use Modules\ServiceContracts\Entities\ServiceContract;
use Modules\Tickets\Entities\Ticket;

/**
 * Class AccountsServiceContractsController
 * @package Modules\Accounts\Http\Controllers\Tabs
 */
class AccountsServiceContractsController extends ModuleCrudRelationController
{
    protected $datatable = AccountServiceContractsDatatable::class;

    protected $ownerModel = Account::class;

    protected $relationModel = ServiceContract::class;

    protected $ownerModuleName = 'accounts';

    protected $relatedModuleName = 'servicecontracts';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'serviceContracts';

    protected $relationType = self::RT_ONE_TO_MANY;

    protected $belongsToName = 'account';

    protected $whereCondition = 'service_contracts.id';
}
