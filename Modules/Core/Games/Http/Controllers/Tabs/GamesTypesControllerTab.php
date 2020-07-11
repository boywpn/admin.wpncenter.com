<?php

namespace Modules\Core\Games\Http\Controllers\Tabs;

use Modules\Core\Games\Datatables\Tabs\GamesTypesDatatableTab;
use Modules\Core\Games\Entities\Games;
use Modules\Core\Games\Entities\GamesTypes;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;

/**
 * Class AccountsTicketsController
 * @package Modules\Contacts\Http\Controllers
 */
class GamesTypesControllerTab extends ModuleCrudRelationController
{
    protected $datatable = GamesTypesDatatableTab::class;

    protected $ownerModel = Games::class;

    protected $relationModel = GamesTypes::class;

    protected $ownerModuleName = 'CoreGames';

    protected $relatedModuleName = 'CoreGames';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'typesGame';

    protected $relationType = self::RT_ONE_TO_MANY;

    protected $belongsToName = 'typesGame';

    protected $whereCondition = 'core_games_types.game_id';

    protected $whereType = self::WHERE_TYPE__COLUMN;
}
