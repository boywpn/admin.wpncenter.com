<?php

namespace Modules\Core\Boards\Http\Controllers\Tabs;

use Modules\Accounts\Datatables\Tabs\AccountTicketsDatatable;
use Modules\Accounts\Entities\Account;
use Modules\Core\Boards\Datatables\Tabs\BoardsMembersDatatableTab;
use Modules\Core\Boards\Datatables\Tabs\BoardsUsersDatatableTab;
use Modules\Core\Boards\Entities\Boards;
use Modules\Core\Boards\Entities\BoardsUsers;
use Modules\Core\Username\Entities\Username;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;
use Modules\Tickets\Entities\Ticket;

/**
 * Class AccountsTicketsController
 * @package Modules\Contacts\Http\Controllers
 */
class BoardsMembersControllerTab extends ModuleCrudRelationController
{
    protected $datatable = BoardsMembersDatatableTab::class;

    protected $ownerModel = Boards::class;

    protected $relationModel = Username::class;

    protected $ownerModuleName = 'CoreBoards';

    protected $relatedModuleName = 'CoreMembers';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'membersBoard';

    protected $relationType = self::RT_ONE_TO_MANY;

    protected $belongsToName = 'usernameBoard';

    protected $whereCondition = 'core_username.board_id';

    protected $whereType = self::WHERE_TYPE__COLUMN;
}
