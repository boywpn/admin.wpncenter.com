<?php

namespace Modules\Core\Boards\Http\Controllers\Tabs;

use Modules\Accounts\Datatables\Tabs\AccountTicketsDatatable;
use Modules\Accounts\Entities\Account;
use Modules\Core\Boards\Datatables\Tabs\BoardsUsersDatatableTab;
use Modules\Core\Boards\Entities\Boards;
use Modules\Core\Boards\Entities\BoardsUsers;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;
use Modules\Tickets\Entities\Ticket;

/**
 * Class AccountsTicketsController
 * @package Modules\Contacts\Http\Controllers
 */
class BoardsUsersControllerTab extends ModuleCrudRelationController
{
    protected $datatable = BoardsUsersDatatableTab::class;

    protected $ownerModel = Boards::class;

    protected $relationModel = BoardsUsers::class;

    protected $ownerModuleName = 'CoreBoards';

    protected $relatedModuleName = 'CoreBoards';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'usersBoard';

    protected $relationType = self::RT_ONE_TO_MANY;

    protected $belongsToName = 'usersBoard';

    protected $whereCondition = 'core_boards_users.board_id';
}
