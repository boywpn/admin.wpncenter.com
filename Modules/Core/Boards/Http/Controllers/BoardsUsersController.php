<?php

namespace Modules\Core\Boards\Http\Controllers;

use Modules\Core\Boards\Datatables\BoardsUsersDatatable;
use Modules\Core\Boards\Entities\BoardsUsers;
use Modules\Core\Boards\Http\Forms\BoardsUsersForm;
use Modules\Core\Boards\Http\Requests\BoardsUsersRequest;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;

class BoardsUsersController extends ModuleCrudController
{
    protected $datatable = BoardsUsersDatatable::class;
    protected $formClass = BoardsUsersForm::class;
    protected $storeRequest = BoardsUsersRequest::class;
    protected $updateRequest = BoardsUsersRequest::class;
    protected $entityClass = BoardsUsers::class;

    protected $moduleName = 'CoreBoards';

    protected $permissions = [
        'browse' => 'core.boards.users.browse',
        'create' => 'core.boards.users.create',
        'update' => 'core.boards.users.update',
        'destroy' => 'core.boards.users.destroy'
    ];

    protected $showFields = [
        'details' => [
            'username' => ['type' => 'text'],
            'password' => ['type' => 'password'],
            'code' => ['type' => 'text'],
            'board_id' => ['type' => 'manyToOne', 'relation' => 'usersBoard', 'column' => 'name'],
            'is_active' => ['type' => 'boolean'],
        ]
    ];

    protected $languageFile = 'core/boards::users';

    protected $routes = [
        'index' => 'core.boards.users.index',
        'create' => 'core.boards.users.create',
        'show' => 'core.boards.users.show',
        'edit' => 'core.boards.users.edit',
        'store' => 'core.boards.users.store',
        'destroy' => 'core.boards.users.destroy',
        'update' => 'core.boards.users.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
