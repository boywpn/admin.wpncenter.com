<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\AppController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Games\Dg\DGController AS DG;
use App\Http\Controllers\Games\Og\OGController AS OG;
use App\Http\Controllers\Games\Sa\SboController AS SA;
use Modules\Core\Boards\Entities\Boards;
use Modules\Core\Games\Entities\GamesTypes;
use Modules\Core\Username\Entities\Username;
use Modules\Member\Members\Entities\MembersCommissions;
use Modules\Report\Betlists\Entities\Betlists;
use Modules\Report\Betlists\Entities\BetlistsResults;

/**
 *
 * Class ContactsApiController
 *
 * @package Modules\Api\Http\Controllers
 */
class SeamlessController extends AppController
{

    /**
     * For SA
    */
    public function SACallback($action)
    {

        echo $action;

    }

}