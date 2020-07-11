<?php

namespace Modules\Campaigns\Http\Controllers\Tabs;

use Modules\Accounts\Entities\Account;
use Modules\Campaigns\Datatables\Tabs\CampaignsAccountsDatatable;
use Modules\Campaigns\Datatables\Tabs\CampaignsContactsDatatable;
use Modules\Campaigns\Entities\Campaign;
use Modules\Contacts\Entities\Contact;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;

/**
 * Class CampaignsAccountsController
 * @package Modules\Campaigns\Http\Controllers
 */
class CampaignsAccountsController extends ModuleCrudRelationController
{
    protected $datatable = CampaignsAccountsDatatable::class;

    protected $ownerModel = Campaign::class;

    protected $relationModel = Account::class;

    protected $ownerModuleName = 'campaigns';

    protected $relatedModuleName = 'accounts';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'accounts';

    protected $whereCondition = 'accounts.id';
}
