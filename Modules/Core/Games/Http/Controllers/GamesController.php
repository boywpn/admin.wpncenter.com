<?php

namespace Modules\Core\Games\Http\Controllers;

use Modules\Core\Games\Datatables\Tabs\GamesTypesDatatableTab;
use Modules\Core\Games\Datatables\GamesDatatable;
use Modules\Core\Games\Entities\Games;
use Modules\Core\Games\Http\Forms\GamesForm;
use Modules\Core\Games\Http\Requests\GamesRequest;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;

class GamesController extends ModuleCrudController
{
    protected $datatable = GamesDatatable::class;
    protected $formClass = GamesForm::class;
    protected $storeRequest = GamesRequest::class;
    protected $updateRequest = GamesRequest::class;
    protected $entityClass = Games::class;

    protected $moduleName = 'CoreGames';

    protected $permissions = [
        'browse' => 'games.browse',
        'create' => 'games.create',
        'update' => 'games.update',
        'destroy' => 'games.destroy'
    ];

    protected $showFields = [

        'information' => [
            'name' => [
                'type' => 'text',
            ],
            'code' => [
                'type' => 'text'
            ],
            'taking' => [
                'type' => 'text'
            ],
            'is_active' => [
                'type' => 'boolean'
            ],
            'is_commission' => [
                'type' => 'boolean'
            ],
            'is_maintenance' => [
                'type' => 'boolean'
            ],
            'maintenance_notes' => [
                'type' => 'text'
            ],
            'member_url' => [
                'type' => 'text',
                'col-class' => 'col-lg-12'
            ]
        ],

    ];

    protected $relationTabs = [
        'types' => [
            'icon' => 'extension',
            'permissions' => [
                'browse' => 'games-types.browse',
                'update' => 'games-types.update',
                'create' => 'games-types.create'
            ],
            'datatable' => [
                'datatable' => GamesTypesDatatableTab::class
            ],
            'route' => [
                'linked' => 'core.games.types.linked',
                'create' => 'core.games-types.create',
                'select' => 'core.games.types.selection',
                'bind_selected' => 'core.games.types.link'
            ],
            'create' => [
                'allow' => true,
                'modal_title' => 'core/games::games-types.create_new',
                'post_create_bind' => [
                    'relationType' => 'oneToMany',
                    'relatedField' => 'game_id',
                ]
            ],
            'select' => [
                'allow' => false,
                'modal_title' => 'core/games::games-types.module'
            ]
        ]
    ];

    protected $languageFile = 'core/games::games';

    protected $routes = [
        'index' => 'core.games.index',
        'create' => 'core.games.create',
        'show' => 'core.games.show',
        'edit' => 'core.games.edit',
        'store' => 'core.games.store',
        'destroy' => 'core.games.destroy',
        'update' => 'core.games.update',
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
