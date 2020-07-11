<?php

namespace Modules\Core\Games\Http\Controllers;

use Modules\Core\Games\Datatables\GamesDatatable;
use Modules\Core\Games\Datatables\GamesTypesDatatable;
use Modules\Core\Games\Entities\Games;
use Modules\Core\Games\Entities\GamesTypes;
use Modules\Core\Games\Http\Forms\GamesForm;
use Modules\Core\Games\Http\Forms\GamesTypesForm;
use Modules\Core\Games\Http\Requests\GamesRequest;
use Modules\Core\Games\Http\Requests\GamesTypesRequest;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;

class GamesTypesController extends ModuleCrudController
{
    protected $datatable = GamesTypesDatatable::class;
    protected $formClass = GamesTypesForm::class;
    protected $storeRequest = GamesTypesRequest::class;
    protected $updateRequest = GamesTypesRequest::class;
    protected $entityClass = GamesTypes::class;

    protected $moduleName = 'CoreGames';

    protected $permissions = [
        'browse' => 'games-types.browse',
        'create' => 'games-types.create',
        'update' => 'games-types.update',
        'destroy' => 'games-types.destroy'
    ];

    protected $showFields = [

        'information' => [
            'game_id' => ['type' => 'manyToOne', 'relation' => 'typesGame', 'column' => 'name', 'col-class' => 'col-lg-4'],
            'name' => [
                'type' => 'text',
                'col-class' => 'col-lg-4'
            ],
            'code' => [
                'type' => 'text',
                'col-class' => 'col-lg-4'
            ],
            'is_active' => [
                'type' => 'boolean',
                'col-class' => 'col-lg-4'
            ],
            'is_commission' => [
                'type' => 'boolean',
                'col-class' => 'col-lg-4'
            ],
            'start_comm' => [
                'type' => 'text',
                'col-class' => 'col-lg-4'
            ],
            'taking' => [
                'type' => 'text',
                'col-class' => 'col-lg-4'
            ],
            'is_betlimit' => [
                'type' => 'boolean',
                'col-class' => 'col-lg-6'
            ],
            'betlimit_value' => [
                'type' => 'text',
                'col-class' => 'col-lg-12'
            ]
        ],

    ];

    protected $languageFile = 'core/games::games-types';

    protected $routes = [
        'index' => 'core.games-types.index',
        'create' => 'core.games-types.create',
        'show' => 'core.games-types.show',
        'edit' => 'core.games-types.edit',
        'store' => 'core.games-types.store',
        'destroy' => 'core.games-types.destroy',
        'update' => 'core.games-types.update',
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
