<?php

namespace Modules\Member\Members\Http\Controllers\Tabs;

use Modules\Core\Username\Entities\Username;
use Modules\Member\Members\Datatables\Tabs\MembersUsernameDatatableTab;
use Modules\Member\Members\Entities\Members;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;

/**
 * Class AccountsTicketsController
 * @package Modules\Contacts\Http\Controllers
 */
class MembersUsernameControllerTab extends ModuleCrudRelationController
{
    protected $datatable = MembersUsernameDatatableTab::class;

    protected $ownerModel = Members::class;

    protected $relationModel = Username::class;

    protected $ownerModuleName = 'MemberMembers';

    protected $relatedModuleName = 'CoreUsername';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'usernameMember';

    protected $relationType = self::RT_ONE_TO_MANY;

    protected $belongsToName = 'usernameMember';

    protected $whereCondition = 'core_username.member_id';

    protected $whereType = self::WHERE_TYPE__COLUMN;
}
