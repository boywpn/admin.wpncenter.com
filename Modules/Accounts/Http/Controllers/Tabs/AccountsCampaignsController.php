<?php

namespace Modules\Accounts\Http\Controllers\Tabs;

use Modules\Accounts\Datatables\Tabs\AccountCampaignsDatatable;
use Modules\Accounts\Entities\Account;
use Modules\Campaigns\Entities\Campaign;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;

class AccountsCampaignsController extends ModuleCrudRelationController
{
    protected $datatable = AccountCampaignsDatatable::class;

    protected $ownerModel = Account::class;

    protected $relationModel = Campaign::class;

    protected $ownerModuleName = 'accounts';

    protected $relatedModuleName = 'campaigns';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'campaigns';

    protected $whereCondition = 'campaigns.id';
}
