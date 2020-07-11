<?php

namespace Modules\Member\Members\Http\Controllers\Tabs;

use Modules\Core\Username\Entities\Username;
use Modules\Member\Members\Datatables\Tabs\MembersBanksDatatableTab;
use Modules\Member\Members\Datatables\Tabs\MembersUsernameDatatableTab;
use Modules\Member\Members\Entities\Members;
use Modules\Member\Members\Entities\MembersBanks;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;

/**
 * Class AccountsTicketsController
 * @package Modules\Contacts\Http\Controllers
 */
class MembersBanksControllerTab extends ModuleCrudRelationController
{
    protected $datatable = MembersBanksDatatableTab::class;

    protected $ownerModel = Members::class;

    protected $relationModel = MembersBanks::class;

    protected $ownerModuleName = 'MemberMembers';

    protected $relatedModuleName = 'MemberMembers';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'banksMember';

    protected $relationType = self::RT_ONE_TO_MANY;

    protected $belongsToName = 'banksMember';

    protected $whereCondition = 'member_members_banks.member_id';

    protected $whereType = self::WHERE_TYPE__COLUMN;
}
