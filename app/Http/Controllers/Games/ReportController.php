<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\AppController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Games\Dg\DGController AS DG;
use App\Http\Controllers\Games\Og\OGController AS OG;
use App\Http\Controllers\Games\Sa\SAController AS SA;
use App\Http\Controllers\Games\Aec\BetsController AS AEC;
use App\Http\Controllers\Games\Sexy\SexyController AS SEXY;
use App\Http\Controllers\Games\Ibc\BetsController AS IBC;
use App\Http\Controllers\Games\Csh\BetsController AS CSH;
use App\Http\Controllers\Games\Pussy\BetsController AS PUSSY;
use App\Http\Controllers\Games\Sbo\BetsController AS SBO;
use App\Http\Controllers\Games\Tfg\BetsController AS TFG;
use App\Http\Controllers\Games\Lotto\BetsController AS LOTTO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Modules\Api\Http\Controllers\Game\TransferApiController;
use Modules\Core\Boards\Entities\Boards;
use Modules\Core\Games\Entities\GamesTypes;
use Modules\Core\Username\Entities\Username;
use Modules\Member\Members\Entities\MembersCommissions;
use Modules\Report\Betlists\Entities\Betlists;
use Modules\Report\Betlists\Entities\BetlistsResults;
use Modules\Report\Betlists\Entities\BetlistsTmp;
use Modules\Report\Commission\Entities\Commission;

/**
 *
 * Class ContactsApiController
 *
 * @package Modules\Api\Http\Controllers
 */
class ReportController extends AppController
{

    public function getBetItem($game, Request $request)
    {

        $get = $request->input();
        $bd_id = (isset($get['bd_id'])) ? $get['bd_id'] : null;

        $this->entityClass = Betlists::class;
        $repository = $this->getRepository();

        $g = explode("_", $game);

        $boards = Boards::getBoardByGameGroup($g[0], $bd_id);

//        print_r($boards->toArray());

        /**
         * AEC Report
        */
        if($game == "aec_tmp"){
            $api = new AEC();
            return $api->betLogTmp($boards);
        }
        elseif($game == "aec_tmp_time"){
            $api = new AEC();
            return $api->betLogTmpByTime($boards);
        }
        elseif($game == "aec_save"){
            $api = new AEC();
            return $api->betLogSave(19);
        }
        /**
         * IBC Report To Tmp
         */
        elseif($game == "ibc_tmp"){
            $api = new IBC();
            return $api->betLogTmp($boards);
        }
        elseif($game == "ibc_tmp_time"){
            $api = new IBC();
            return $api->betLogTmpByTime($boards);
        }
        elseif($game == "ibc_save"){
            $api = new IBC();
            return $api->betLogSave(21);
        }
        elseif($game == "ibc_save_time"){
            $api = new IBC();
            return $api->betLogSaveTime(21);
        }
        elseif($game == "ibc_save_bup"){
            $api = new IBC();
            return $api->betLogSaveBup(21);
        }

        /**
         * LottoSH
         */
        elseif($game == "lottosh"){
            $api = new LOTTO();
            return $api->betLogTmp($boards);
        }
        elseif($game == "lottosh_save"){
            $api = new LOTTO();
            return $api->betLogSave(23);
        }

        /**
         * CasinoSH
         */
        elseif($game == "csh"){

            $api = new CSH();
            return $api->betLogTmp($boards);

        }
        elseif($game == "csh_fix"){

            $api = new CSH();
            return $api->betLogTmpFix($boards);

        }
        elseif($game == "csh_time"){

            $api = new CSH();
            return $api->betLogTmpByTime($boards);

        }
        elseif($game == "csh_board"){

            $api = new CSH();
            $boards = Boards::getBoardByID(71); // 64=sh, 65=wpn, 66=atb, 70=gl, 71=ma
            return $api->betLogTmp($boards);

        }
        elseif($game == "csh_save"){

//            if(!isset($get['ref'])){
//                return "The ref field is required.";
//            }

            $api = new CSH();
//            return $api->betLogSave(25, $get['ref']);
            return $api->betLogSave(25);

        }

        /**
         * SBO API
         */
        elseif($game == "sboapi"){
            if(!isset($get['portfolio'])){
                return "The portfolio field is required.";
            }
            $api = new SBO();
            return $api->betLogTmp($boards, $get['portfolio']);
        }
        elseif($game == "sboapi_date"){
//            if(!isset($get['portfolio'])){
//                return "The portfolio field is required.";
//            }
            if(!isset($get['date'])){
                return "The date field is required.";
            }
            $api = new SBO();

            if(empty($get['portfolio'])) {
                $arrPorts = ['SportsBook', 'Casino', 'Games', 'VirtualSports', 'SeamlessGame'];
                $arrRes = [];
                foreach ($arrPorts as $port) {
                    $arrRes[$port] = $api->betLogTmp($boards, $port, $get['date']);
                }
                return $arrRes;
            }else{
                return $api->betLogTmp($boards, $get['portfolio'], $get['date']);
            }
        }
        elseif($game == "sboapi_save"){
            $api = new SBO();
            return $api->betLogSave(24);
        }
        elseif($game == "sboapi_transactions"){
            $api = new SBO();
            return $api->getTransactions($get, true);
        }
        elseif($game == "sboapi_save_status"){
            if(!isset($get['status'])){
                return "The status field is required.";
            }
            if(!isset($get['limit'])){
                return "The limit field is required.";
            }
            $api = new SBO();
            return $api->betLogSave(24, $get['limit'], $get['status']);
        }
        elseif($game == "sboapi_fixcomm"){
            if(!isset($get['game_type'])){
                return "The game_type field is required.";
            }
            $api = new SBO();
            return $api->betFixComm(24, $get['game_type']);
        }
        elseif($game == "sboapi_fixdate"){
            if(!isset($get['game_type'])){
                return "The game_type field is required.";
            }
            $api = new SBO();
            return $api->betFixDate(24, $get['game_type']);
        }
        elseif($game == "sboapi_fixcomm_by_user"){
            if(!isset($get['username_id'])){
                return "The username_id field is required.";
            }
            $api = new SBO();
            return $api->fixCommByUser($get['username_id']);
        }

        /**
         * TFGaming
         */
        elseif($game == "tfg"){
            $api = new TFG();
            return $api->betLogTmp($boards);
        }
        elseif($game == "tfg_save"){
            $api = new TFG();
            return $api->betLogSave(27);
        }

    }

    public function listBetLimit($board_id){

        $board = Boards::where('core_boards.id', $board_id)
            ->leftJoin('core_games', 'core_boards.game_id', '=', 'core_games.id')
            ->first();


        if(!$board){
            echo "Board don't exist.";
        }

        $board = $board->toArray();

        $key = json_decode($board['api_code'], true);

        if($board['code'] == "sa"){

            $api = new SA($key);

            $res = $api->getBetLimitList();

        }

        foreach ($res['BetLimitList']['BetLimit'] as $key => $limit){

            print $limit['RuleID'] . ": " . $limit['Min'] . " to " . $limit['Max']."\r\n";

        }

    }

    public function getBalanceByBoard($entity_id, $username){

        if($entity_id != 4){
            exit;
        }

        $users = Boards::where('id', $entity_id)
            ->with(['membersBoard' => function($query){
                $query->where('is_created', 1)
                    ->select('id', 'board_id', 'username');
            }, 'boardsGame' => function($query){
                $query->select('*');
            }])
            ->select('id', 'name', 'api_code', 'game_id')
            ->first()
            ->toArray();

        $key = json_decode($users['api_code'], true);

        // $repository = $this->getRepository();

        $data = [];
        foreach ($users['members_board'] as $user){

            if($users['boards_game']['code'] == "sa"){

                $api = new SA($key);

                $username = $user['username'];

                // check exist
                $setParam = [
                    'method' => 'GetUserStatusDV',
                    'Username' => $username,
                ];
                $api->setParam($setParam);
                $response = $api->push();
                $res = xmlDecode($response, true);

                $data[] = [
                    'Username' => $res['Username'],
                    'Balance' => $res['Balance'],
                ];

            }

        }

        return $data;

    }

    public function getFixtures($type_id, $type_name){

        $lists = Betlists::where('game_type_id', $type_id)
            ->where('bet_type', $type_name)
            ->with(['listsResult' => function($q){
                $q->select('id', 'betlist_id', 'game_result')->orderBy('id', 'desc');
            }])
            ->select('id', 'board_game_id', 'work_time', 'bet_amount', 'state')
            ->orderBy('id', 'asc')
            ->limit(10)
            ->get();

        foreach ($lists as $list){

            $list = $list->toArray();

            $data = $list['lists_result']['game_result'];
            $result[] = $this->saveFixture($list['id'] ,$list['board_game_id'], $data);

        }

        return $result;

    }

}