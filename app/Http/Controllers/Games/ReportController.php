<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\AppController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Games\Dg\DGController AS DG;
use App\Http\Controllers\Games\Og\OGController AS OG;
use App\Http\Controllers\Games\Sa\SAController AS SA;
use App\Http\Controllers\Games\Aec\AecController AS AEC;
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

    public function getBetItemByUser($game)
    {

        // return ['status' => false, 'msg' => 'Maintenance'];

        $this->entityClass = Betlists::class;

        $usernames = Username::whereNotNull('core_username.member_id')
            // ->where('core_username.is_created', 1)
            // ->where('core_username.is_active', 1)
            // ->whereNotNull('core_username.member_id')
            ->leftJoin('core_boards', 'core_username.board_id', '=', 'core_boards.id')
            ->leftJoin('core_games', 'core_boards.game_id', '=', 'core_games.id')
            ->leftJoin('member_members', 'core_username.member_id', '=', 'member_members.id')
            ->where('core_boards.report_api', 1)
            ->where('core_boards.is_active', 1)
            ->where('core_games.code', $game)
            ->select(
                'core_username.id as username_id',
                'core_username.username',
                'core_username.next_start',
                'core_username.member_id as member_id',
                'core_boards.id as board_id',
                'core_boards.name as board_name',
                'core_boards.api_code as api_code',
                'core_games.id as game_id',
                'core_games.name as game_name',
                'core_games.code as game_code',
                'core_games.taking as game_taking',
                'member_members.agent_id as agent_id'
            )
            ->get();

//        print_r($usernames->toArray());
//        exit;
        $datetime = genDate('0 minute');

        $repository = $this->getRepository();

        foreach ($usernames->toArray() as $username){

            $key = json_decode($username['api_code'], true);

            $member_id = $username['member_id'];
            $agent_id = $username['agent_id'];
            $username_id = $username['username_id'];
            $board_game_id = $username['game_id'];

            // Check taking
            $super_taking = $username['game_taking'] / 100;
            $company_taking = 1 - $super_taking;
            $agent_taking = 0;

            if($username['game_code'] == "sa"){

                $api = new SA($key);

                $bet = $api->getBetItem($username['username'], $username['next_start']);
//                $bet = $api->getBetItem($username['username'], 0);

                print_r($bet);

                // If not have bet items
                if($bet['ItemCount'] == 0){
                    continue;
                }

                if($bet['ItemCount'] > 1){
                    $betlist = $bet['UserBetItemList']['UserBetItem'];
                }else{
                    $betlist[0] = $bet['UserBetItemList']['UserBetItem'];
                }

                // get member comm
                $comm = MembersCommissions::getMemberCommissions($member_id);

                $last_id = 0;

                foreach ($betlist as $item){

                    $exist = Betlists::where('board_game_id', $board_game_id)
                        ->where('bet_id', $item['BetID'])
                        ->count();

                    if($exist == 0) { // If exist skip

                        $game_type_id = GamesTypes::getIdFromCode($item['GameType']);

                        // check commission
                        $commission = 0;
                        $commission_amount = 0;
                        if (isset($comm[$board_game_id][$game_type_id])) {
                            $commission = $comm[$board_game_id][$game_type_id] / 100; // b/c show on system 0.00 - 1.00
                            $commission_amount = $item['Rolling'] * $commission;
                        }

                        $result_winloss = $item['ResultAmount'];
                        $result_rolling = $item['Rolling']; // use for calculate commission
                        // check taking
                        $taking_winloss = $item['ResultAmount'] * -1;
                        $taking_commission = $commission_amount * -1;

                        // agent
                        $agent_winloss = 0;
                        $agent_amount = 0;
                        $agent_commission = 0;

                        // super taking amount and comm
                        $super_winloss = $taking_winloss * $super_taking;
                        $super_amount = $result_rolling * $super_taking;
                        $super_commission = $taking_commission; // super response 100%

                        // company
                        $company_winloss = $taking_winloss - $super_winloss;
                        $company_amount = $result_rolling - $super_amount;
                        $company_commission = 0;

                        $arrItem = [
                            'member_id' => $member_id,
                            'agent_id' => $agent_id,
                            'username_id' => $username_id,
                            'board_game_id' => $board_game_id,
                            'game_type_id' => $game_type_id,
                            'bet_id' => $item['BetID'],
                            'bet_time' => $item['BetTime'],
                            'payout_time' => $item['PayoutTime'],
                            'game_id' => $item['GameID'],
                            'host_id' => $item['HostID'],
                            'host_name' => (is_array($item['HostName'])) ? ((count($item['HostName']) > 0) ? json_encode($item['HostName']) : null) : $item['HostName'],
                            'game_type' => $item['GameType'],
                            'set' => $item['Set'],
                            'round' => $item['Round'],
                            'bet_type' => $item['BetType'],
                            'bet_amount' => $item['BetAmount'],
                            'rolling' => $item['Rolling'],
                            'detail' => (is_array($item['Detail'])) ? ((count($item['Detail']) > 0) ? json_encode($item['Detail']) : null) : $item['Detail'],
                            'result_amount' => $result_winloss,
                            'balance' => $item['Balance'],
                            'state' => $item['State'],
                            'commission' => (string)$commission,
                            'commission_amount' => (string)$commission_amount,

                            'agent_taking' => (string)$agent_taking,
                            'agent_winloss' => (string)$agent_winloss,
                            'agent_amount' => (string)$agent_amount,
                            'agent_commission' => (string)$agent_commission,

                            'super_taking' => (string)$super_taking,
                            'super_winloss' => (string)$super_winloss,
                            'super_amount' => (string)$super_amount,
                            'super_commission' => (string)$super_commission,

                            'company_taking' => (string)$company_taking,
                            'company_winloss' => (string)$company_winloss,
                            'company_amount' => (string)$company_amount,
                            'company_commission' => (string)$company_commission,
                            'trans_commission_status' => 1,
                        ];

                        $entity = $repository->createEntity($arrItem, \App::make($this->entityClass));

                        $arrResult = [
                            'betlist_id' => $entity->id,
                            'game_result' => (count($item['GameResult']) > 0) ? json_encode($item['GameResult']) : null
                        ];
                        $repository->createEntity($arrResult, \App::make(BetlistsResults::class));
                    }

                    $last_id = $item['BetID'];
                }

                // Update Username last action data
                $arrUsername = [
                    'betitem_at' => $datetime,
                    'next_start' => ($bet['Offset'] != 0) ? $bet['Offset'] : $last_id,
                    'from_time' => str_replace("T", " ",$bet['FromTime']),
                    'to_time' => str_replace("T", " ",$bet['ToTime']),
                ];
//                print_r($arrUsername);
                Username::find($username['username_id'])->update($arrUsername);

            }

        }

    }

    public function getBetItem($game, Request $request)
    {

        $get = $request->input();
        $bd_id = (isset($get['bd_id'])) ? $get['bd_id'] : null;

        $this->entityClass = Betlists::class;
        $repository = $this->getRepository();

        $g = explode("_", $game);

        $boards = Boards::getBoardByGameGroup($g[0], $bd_id);

//        print_r($boards->toArray());

        if($game == "dg"){

            foreach ($boards as $board){

                $key = json_decode($board['api_code'], true);
                $api = new DG($key);

                $arrList = [];
                $bets = $api->getBetItem();

//                print_r($bets);

                if(!isset($bets['list'])){
                    continue;
                }

                foreach ($bets['list'] as $item){
//                    print_r($item);
                    $username = Username::getUsernameByUser($item['userName']);
//                    print_r($username);

                    $member_id = $username['member_id'];
                    $agent_id = $username['agent_id'];
                    $username_id = $username['username_id'];
                    $board_game_id = $username['game_id'];

                    // Check Exist
                    $exist = Betlists::where('board_game_id', $board_game_id)
                        ->where('bet_id', $item['id'])
                        ->count();

                    if($exist == 0) { // If exist skip

                        $arrList[] = $item['id']; // Prepare for mark complete

                        // get member comm
                        $comm = MembersCommissions::getMemberCommissions($member_id);
//                        print_r($comm);

                        // Check Game Type
                        $gameType = GamesTypes::getTypeByCode($item['gameType'].".".$item['gameId'], $username['game_id']);
                        $game_type_id = $gameType['id'];
//                        print_r($gameType);

                        // Check taking
                        $super_taking = $gameType['taking'] / 100;
                        $company_taking = 1 - $super_taking;
                        $agent_taking = 0;

                        // check commission
                        $commission = 0;
                        $commission_amount = 0;
                        if (isset($comm[$board_game_id][$game_type_id])) {
                            $commission = $comm[$board_game_id][$game_type_id] / 100; // b/c show on system 0.00 - 1.00
                            $commission_amount = $item['availableBet'] * $commission;
                        }

                        $result_amount = $item['winOrLoss'] - $item['betPoints'];

                        $result_winloss = $result_amount;
                        $result_rolling = $item['availableBet']; // use for calculate commission
                        // check taking
                        $taking_winloss = $result_amount * -1;
                        $taking_commission = $commission_amount * -1;

                        // agent
                        $agent_winloss = 0;
                        $agent_amount = 0;
                        $agent_commission = 0;

                        // super taking amount and comm
                        $super_winloss = $taking_winloss * $super_taking;
                        $super_amount = $result_rolling * $super_taking;
                        $super_commission = $taking_commission; // super response 100%

                        // company
                        $company_winloss = $taking_winloss - $super_winloss;
                        $company_amount = $result_rolling - $super_amount;
                        $company_commission = 0;

                        $arrItem = [
                            'member_id' => $member_id,
                            'agent_id' => $agent_id,
                            'username' => $item['userName'],
                            'username_id' => $username_id,
                            'board_game_id' => $board_game_id,
                            'game_type_id' => $game_type_id,
                            'bet_id' => $item['id'],
                            'bet_time' => $item['betTime'],
                            'payout_time' => $item['calTime'],
                            'game_id' => $item['gameId'],
                            'host_id' => $item['tableId'],
                            'host_name' => $item['memberId']."|".$item['parentId'],
                            'game_type' => $item['gameType'],
                            'set' => $item['shoeId'],
                            'round' => $item['playId'],
                            'bet_type' => $item['gameType'],
                            'bet_amount' => $item['betPoints'],
                            'rolling' => $item['availableBet'],
                            'detail' => $item['betDetail'],
                            'result_amount' => $result_winloss,
                            'balance' => $item['balanceBefore'],
                            'state' => 'true',
                            'commission' => (string)$commission,
                            'commission_amount' => (string)$commission_amount,

                            'agent_taking' => (string)$agent_taking,
                            'agent_winloss' => (string)$agent_winloss,
                            'agent_amount' => (string)$agent_amount,
                            'agent_commission' => (string)$agent_commission,

                            'super_taking' => (string)$super_taking,
                            'super_winloss' => (string)$super_winloss,
                            'super_amount' => (string)$super_amount,
                            'super_commission' => (string)$super_commission,

                            'company_taking' => (string)$company_taking,
                            'company_winloss' => (string)$company_winloss,
                            'company_amount' => (string)$company_amount,
                            'company_commission' => (string)$company_commission,
                            'trans_commission_status' => 1,
                        ];

                        $entity = $repository->createEntity($arrItem, \App::make($this->entityClass));

                        $arrResult = [
                            'betlist_id' => $entity->id,
                            'game_result' => $item['result']
                        ];
                        $repository->createEntity($arrResult, \App::make(BetlistsResults::class));

                    }

                } // End foreach list

                $api->markBetItem($arrList); // Mark Completed

            } // End foreach boards

        }

        /**
         * SA Gaming
         *
         * SA Gaming Generate Report
         */
        elseif($game == "sa_gen"){

            $arrList = [];
            $arrAdd = [];
            $arrUpdate = [];
            $game_id = 3;

            $bets = BetlistsTmp::where('game_id', $game_id)
                ->whereNull('status')
                // ->whereDate('payout_time', '=', date('Y-m-d'))
                ->orderBy('id', 'ASC')
                // ->limit(100)
                ->get();

            // print_r($bets);

            foreach ($bets as $items) {

                $id = $items->id;
                $bet_id = $items->bet_id;
                $item = json_decode($items->data, true);

                // Check Exist
                $exist = Betlists::where('board_game_id', $game_id)
                    ->where('bet_id', $bet_id)
                    ->first();

                if(empty($exist)) {

                    $username = Username::getUsernameByUser($item['Username']);

                    $member_id = $username['member_id'];
                    $agent_id = $username['agent_id'];
                    $username_id = $username['username_id'];
                    $board_game_id = $username['game_id'];

                    // get member comm
                    $comm = MembersCommissions::getMemberCommissions($member_id);

                    // Check Game Type
                    $gameType = GamesTypes::getTypeByCode($item['GameType'], $board_game_id);
                    $game_type_id = $gameType['id'];

//                    print_r($gameType);

                    // Check taking
                    $super_taking = $gameType['taking'] / 100;
                    $company_taking = 1 - $super_taking;
                    $agent_taking = 0;

                    // check commission
                    $commission = 0;
                    $commission_amount = 0;
                    if (isset($comm[$board_game_id][$game_type_id])) {
                        $commission = $comm[$board_game_id][$game_type_id] / 100; // b/c show on system 0.00 - 1.00
                        $commission_amount = $item['Rolling'] * $commission;
                    }

                    $result_amount = $item['ResultAmount'];
                    $result_winloss = $result_amount;
                    $result_rolling = $item['Rolling']; // use for calculate commission
                    // check taking
                    $taking_winloss = $result_amount * -1;
                    $taking_commission = $commission_amount * -1;

                    // agent
                    $agent_winloss = 0;
                    $agent_amount = 0;
                    $agent_commission = 0;

                    // super taking amount and comm
                    $super_winloss = $taking_winloss * $super_taking;
                    $super_amount = $result_rolling * $super_taking;
                    $super_commission = $taking_commission; // super response 100%

                    // company
                    $company_winloss = $taking_winloss - $super_winloss;
                    $company_amount = $result_rolling - $super_amount;
                    $company_commission = 0;

                    $arrItem = [
                        'member_id' => $member_id,
                        'agent_id' => $agent_id,
                        'username' => $item['Username'],
                        'username_id' => $username_id,
                        'board_game_id' => $board_game_id,
                        'game_type_id' => $game_type_id,
                        'bet_id' => $item['BetID'],
                        'bet_time' => $item['BetTime'],
                        'payout_time' => $item['PayoutTime'],
                        'work_time' => null,
                        'match_time' => null,
                        'game_id' => $item['GameID'],
                        'host_id' => $item['HostID'],
                        'host_name' => null,
                        'game_type' => $item['GameType'],
                        'set' => $item['Set'],
                        'round' => $item['Round'],
                        'bet_type' => $item['BetType'],
                        'bet_amount' => $item['BetAmount'],
                        'rolling' => (string)$item['Rolling'],
                        'detail' => (is_array($item['Detail'])) ? ((count($item['Detail']) > 0) ? json_encode($item['Detail']) : null) : $item['Detail'],
                        'result_amount' => (string)$result_winloss,
                        'balance' => $item['Balance'],
                        'state' => (isset($item['State'])) ? $item['State'] : null,
                        'commission' => (string)$commission,
                        'commission_amount' => (string)$commission_amount,

                        'agent_taking' => (string)$agent_taking,
                        'agent_winloss' => (string)$agent_winloss,
                        'agent_amount' => (string)$agent_amount,
                        'agent_commission' => (string)$agent_commission,

                        'super_taking' => (string)$super_taking,
                        'super_winloss' => (string)$super_winloss,
                        'super_amount' => (string)$super_amount,
                        'super_commission' => (string)$super_commission,

                        'company_taking' => (string)$company_taking,
                        'company_winloss' => (string)$company_winloss,
                        'company_amount' => (string)$company_amount,
                        'company_commission' => (string)$company_commission,
                        'trans_commission_status' => 1
                    ];

                    $arrAdd[] = $arrItem;

                    $entity = $repository->createEntity($arrItem, \App::make($this->entityClass));

                    $arrResult = [
                        'betlist_id' => $entity->id,
                        'game_result' => json_encode($item['GameResult'], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)
                    ];
                    $repository->createEntity($arrResult, \App::make(BetlistsResults::class));

                    /**
                     * Update Tmp
                     */
                    BetlistsTmp::where(['id' => $id])->update(['status' => 1, 'request_at' => date('Y-m-d H:i:s'), 'betlist_id' => $entity->id]);
                }
                /**
                 * Update
                 */
                else{

                    $arrUpdate[] = $item;

                    /**
                     * Update Tmp
                     */
                    BetlistsTmp::where(['id' => $id])->update(['status' => 2, 'request_at' => date('Y-m-d H:i:s'), 'betlist_id' => $exist->id]);

                }

            }

            $arrList = [
                'add' => $arrAdd,
                'update' => $arrUpdate
            ];

            print_r($arrList);

        }
        /**
         * SA Gaming Get Tmp
         */
        elseif($game == "sa_tmp"){

            foreach ($boards as $board){

                $key = json_decode($board['api_code'], true);
                $api = new SA($key);

                $arrList = [];
                $arrAdd = [];
                $arrUpdate = [];
                $bets = $api->getBetItems();

                // print_r($bets);
                // continue;

                if($bets['ErrorMsgId'] != 0){
                    continue;
                }

                if(count($bets['BetDetailList']) == 0){
                    continue;
                }

                if(count($bets['BetDetailList']['BetDetail']) == 0){
                    continue;
                }

                foreach ($bets['BetDetailList']['BetDetail'] as $item) {

                    $username = Username::getUsernameByUser($item['Username']);

                    $member_id = $username['member_id'];
                    $agent_id = $username['agent_id'];
                    $username_id = $username['username_id'];
                    $board_game_id = $username['game_id'];

                    $exist = BetlistsTmp::where('game_id', $board_game_id)
                        ->where('bet_id', $item['BetID'])
                        ->first();

                    if(empty($exist)) {

                        $arrTmp = [
                            'game_id' => $board_game_id,
                            'bet_id' => $item['BetID'],
                            'data' => json_encode($item, JSON_UNESCAPED_UNICODE),
                        ];
                        $entity = $repository->createEntity($arrTmp, \App::make(BetlistsTmp::class));

                    }
                    /**
                     * Update
                     */
                    else{

                        $arrUpdate[] = $item;

                    }

                }

                $arrList = [
                    'add' => $arrAdd,
                    'update' => $arrUpdate
                ];

                print_r($arrList);

            } // End foreach boards

        }
        /**
         * SA Gaming Get Fix
         */
        elseif($game == "sa_fix"){

            foreach ($boards as $board){

                $key = json_decode($board['api_code'], true);
                $api = new SA($key);

                $arrList = [];
                $arrAdd = [];
                $arrUpdate = [];
                $bets = $api->getBetItemsFixtime();

//                print_r($bets);
//                continue;

                if($bets['ErrorMsgId'] != 0){
                    continue;
                }

                if(count($bets['BetDetailList']) == 0){
                    continue;
                }

                if(count($bets['BetDetailList']['BetDetail']) == 0){
                    continue;
                }

                $items = $bets['BetDetailList']['BetDetail'];
                foreach ($items as $item) {

                    $username = Username::getUsernameByUser($item['Username']);

                    $member_id = $username['member_id'];
                    $agent_id = $username['agent_id'];
                    $username_id = $username['username_id'];
                    $board_game_id = $username['game_id'];

                    $exist = Betlists::where('board_game_id', $board_game_id)
                        ->where('bet_id', $item['BetID'])
                        ->first();

                    if(empty($exist)) {

                        $arrAdd[] = $item;

                        $arrTmp = [
                            'game_id' => $board_game_id,
                            'bet_id' => $item['BetID'],
                            'data' => json_encode($item, JSON_UNESCAPED_UNICODE),
                        ];
                        $entity = $repository->createEntity($arrTmp, \App::make(BetlistsTmp::class));

                    }
                    /**
                     * Update
                     */
                    else{

                        $arrUpdate[] = $item;

                    }

                }

                $arrList = [
                    'add_record' => count($arrAdd),
                    'add' => $arrAdd,
                    'update_record' => count($arrUpdate),
                    'update' => $arrUpdate,
                ];

                print_r($arrList);

            } // End foreach boards

        }

        /**
         * AEC Report
        */
        elseif($game == "aec"){

            $date = date('Y-m-d H:i:s');
            $sDate = date('Y-m-d H:i:s', strtotime('50 minutes', strtotime($date)));
            $eDate = date('Y-m-d H:i:s', strtotime('60 minutes', strtotime($date)));

            $game_type = "sportsbook";

            foreach ($boards as $board){

                $key = json_decode($board['api_code'], true);
                $api = new AEC($key);

                $arrList = [];
                $arrAdd = [];
                $arrUpdate = [];
                $bets = $api->getBetItem($key['agent'], $game_type, $sDate, $eDate);

//                print_r($bets);

                if(count($bets['playerBetList']) == 0){
                    continue;
                }

                foreach ($bets['playerBetList'] as $item) {
//                    print_r($item);
                    $username = Username::getUsernameByUser($item['u']);
//                    print_r($username);

                    $member_id = $username['member_id'];
                    $agent_id = $username['agent_id'];
                    $username_id = $username['username_id'];
                    $board_game_id = $username['game_id'];

                    // get member comm
                    $comm = MembersCommissions::getMemberCommissions($member_id);

                    // Check Game Type
                    $gameType = GamesTypes::getTypeByCode($game_type, $username['game_id']);
                    $game_type_id = $gameType['id'];
//                        print_r($gameType);
//                        continue;

                    // Check taking
                    $super_taking = $gameType['taking'] / 100;
                    $company_taking = 1 - $super_taking;
                    $agent_taking = 0;

                    // check commission
                    $commission = 0;
                    $commission_amount = 0;
                    if (isset($comm[$board_game_id][$game_type_id])) {
                        $commission = $comm[$board_game_id][$game_type_id] / 100; // b/c show on system 0.00 - 1.00
                        $commission_amount = $item['ValidAmt'] * $commission;
                    }

                    $result_amount = $item['w'];

                    $result_winloss = $result_amount;
                    $result_rolling = $item['ValidAmt']; // use for calculate commission
                    // check taking
                    $taking_winloss = $result_amount * -1;
                    $taking_commission = $commission_amount * -1;

                    // agent
                    $agent_winloss = 0;
                    $agent_amount = 0;
                    $agent_commission = 0;

                    // super taking amount and comm
                    $super_winloss = $taking_winloss * $super_taking;
                    $super_amount = $result_rolling * $super_taking;
                    $super_commission = $taking_commission; // super response 100%

                    // company
                    $company_winloss = $taking_winloss - $super_winloss;
                    $company_amount = $result_rolling - $super_amount;
                    $company_commission = 0;

                    $detail = json_encode($item, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

                    $hash = md5($item['u'].$item['ventransid']);

                    $arrItem = [
                        'member_id' => $member_id,
                        'agent_id' => $agent_id,
                        'username' => $item['u'],
                        'username_id' => $username_id,
                        'board_game_id' => $board_game_id,
                        'game_type_id' => $game_type_id,
                        'bet_id' => $item['ventransid'],
                        'hash' => $hash,
                        'bet_time' => $item['trandate'],
                        'payout_time' => $item['t'],
                        'work_time' => $item['workdate'],
                        'match_time' => $item['matchdate'],
                        'game_id' => $item['id'],
                        'host_id' => $item['ip'],
                        'host_name' => null,
                        'game_type' => $game_type,
                        'set' => null,
                        'round' => null,
                        'bet_type' => $item['sportstype'],
                        'bet_amount' => $item['b'],
                        'turnover' => $item['b'],
                        'rolling' => $item['ValidAmt'],
                        'result_amount' => $result_winloss,
                        'balance' => null,
                        'state' => $item['status'],
                        'commission' => (string)$commission,
                        'commission_amount' => (string)$commission_amount,

                        'agent_taking' => (string)$agent_taking,
                        'agent_winloss' => (string)$agent_winloss,
                        'agent_amount' => (string)$agent_amount,
                        'agent_commission' => (string)$agent_commission,

                        'super_taking' => (string)$super_taking,
                        'super_winloss' => (string)$super_winloss,
                        'super_amount' => (string)$super_amount,
                        'super_commission' => (string)$super_commission,

                        'company_taking' => (string)$company_taking,
                        'company_winloss' => (string)$company_winloss,
                        'company_amount' => (string)$company_amount,
                        'company_commission' => (string)$company_commission,
                        'trans_commission_status' => 1,
                    ];

                    // Check Exist
                    $exist = Betlists::where('hash', $hash)->first();

                    if(empty($exist)) {

                        $arrAdd[] = $arrItem;

                        $entity = $repository->createEntity($arrItem, \App::make($this->entityClass));

                        $arrResult = [
                            'betlist_id' => $entity->id,
                            'game_result' => $detail
                        ];
                        $repository->createEntity($arrResult, \App::make(BetlistsResults::class));
                    }
                    /**
                     * Update
                    */
                    else{

                        $arrUpdate[] = $arrItem;

                        $repository->setupModel($this->entityClass);
                        $entity = $repository->find($exist->id);
                        $repository->updateEntity($arrItem, $entity);

                        $arrResult = [
                            'betlist_id' => $exist->id,
                            'game_result' => $detail
                        ];
                        $repository->createEntity($arrResult, \App::make(BetlistsResults::class));

                        /**
                         * Update Result
                        */
                        BetlistsResults::where(['betlist_id' => $exist->id])->update(['game_result' => $detail]);

                    }

                }

                $arrList = [
                    'sdate' => $sDate,
                    'edate' => $eDate,
                    'add' => $arrAdd,
                    'update' => $arrUpdate
                ];

                print_r($arrList);

            } // End foreach boards

        }
        elseif($game == "aec_fix"){

            $sDate = '2020-07-16';
            $eDate = '2020-07-17';

            $game_type = "sportsbook";

//            print_r($boards);

            foreach ($boards as $board){

                $key = json_decode($board['api_code'], true);
                $api = new AEC($key);

                $arrList = [];
                $arrAdd = [];
                $arrUpdate = [];
                $bets = $api->getBetItem($key['agent'], $game_type, $sDate, $eDate);

//                print_r($bets);
//                continue;

                if(count($bets['playerBetList']) == 0){
                    continue;
                }

                foreach ($bets['playerBetList'] as $item) {
//                    print_r($item);
                    $username = Username::getUsernameByUser($item['u']);
//                    print_r($username);

                    $member_id = $username['member_id'];
                    $agent_id = $username['agent_id'];
                    $username_id = $username['username_id'];
                    $board_game_id = $username['game_id'];

                    // get member comm
                    $comm = MembersCommissions::getMemberCommissions($member_id);

                    // Check Game Type
                    $gameType = GamesTypes::getTypeByCode($game_type, $username['game_id']);
                    $game_type_id = $gameType['id'];
//                        print_r($gameType);
//                        continue;

                    // Check taking
                    $super_taking = $gameType['taking'] / 100;
                    $company_taking = 1 - $super_taking;
                    $agent_taking = 0;

                    // check commission
                    $commission = 0;
                    $commission_amount = 0;
                    if (isset($comm[$board_game_id][$game_type_id])) {
                        $commission = $comm[$board_game_id][$game_type_id] / 100; // b/c show on system 0.00 - 1.00
                        $commission_amount = $item['ValidAmt'] * $commission;
                    }

                    $result_amount = $item['w'];

                    $result_winloss = $result_amount;
                    $result_rolling = $item['ValidAmt']; // use for calculate commission
                    // check taking
                    $taking_winloss = $result_amount * -1;
                    $taking_commission = $commission_amount * -1;

                    // agent
                    $agent_winloss = 0;
                    $agent_amount = 0;
                    $agent_commission = 0;

                    // super taking amount and comm
                    $super_winloss = $taking_winloss * $super_taking;
                    $super_amount = $result_rolling * $super_taking;
                    $super_commission = $taking_commission; // super response 100%

                    // company
                    $company_winloss = $taking_winloss - $super_winloss;
                    $company_amount = $result_rolling - $super_amount;
                    $company_commission = 0;

                    $detail = json_encode($item, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

                    $hash = md5($item['u'].$item['ventransid']);

                    $arrItem = [
                        'member_id' => $member_id,
                        'agent_id' => $agent_id,
                        'username' => $item['u'],
                        'username_id' => $username_id,
                        'board_game_id' => $board_game_id,
                        'game_type_id' => $game_type_id,
                        'bet_id' => $item['ventransid'],
                        'hash' => $hash,
                        'bet_time' => $item['trandate'],
                        'payout_time' => $item['t'],
                        'work_time' => $item['workdate'],
                        'match_time' => $item['matchdate'],
                        'game_id' => $item['id'],
                        'host_id' => $item['ip'],
                        'host_name' => null,
                        'game_type' => $game_type,
                        'set' => null,
                        'round' => null,
                        'bet_type' => $item['sportstype'],
                        'bet_amount' => $item['b'],
                        'turnover' => $item['b'],
                        'rolling' => $item['ValidAmt'],
                        'result_amount' => $result_winloss,
                        'balance' => null,
                        'state' => $item['status'],
                        'commission' => (string)$commission,
                        'commission_amount' => (string)$commission_amount,

                        'agent_taking' => (string)$agent_taking,
                        'agent_winloss' => (string)$agent_winloss,
                        'agent_amount' => (string)$agent_amount,
                        'agent_commission' => (string)$agent_commission,

                        'super_taking' => (string)$super_taking,
                        'super_winloss' => (string)$super_winloss,
                        'super_amount' => (string)$super_amount,
                        'super_commission' => (string)$super_commission,

                        'company_taking' => (string)$company_taking,
                        'company_winloss' => (string)$company_winloss,
                        'company_amount' => (string)$company_amount,
                        'company_commission' => (string)$company_commission,
                        'trans_commission_status' => 1,
                    ];

                    // Check Exist
                    $exist = Betlists::where('hash', $hash)->first();

                    if(empty($exist)) {

                        $arrAdd[] = $arrItem;

                        $entity = $repository->createEntity($arrItem, \App::make($this->entityClass));

                        $arrResult = [
                            'betlist_id' => $entity->id,
                            'game_result' => $detail
                        ];
                        $repository->createEntity($arrResult, \App::make(BetlistsResults::class));
                    }
                    /**
                     * Update
                     */
                    else{

                        $arrUpdate[] = $arrItem;

                        $repository->setupModel($this->entityClass);
                        $entity = $repository->find($exist->id);
                        $repository->updateEntity($arrItem, $entity);

                        $arrResult = [
                            'betlist_id' => $exist->id,
                            'game_result' => $detail
                        ];
                        $repository->createEntity($arrResult, \App::make(BetlistsResults::class));

                        /**
                         * Update Result
                         */
                        BetlistsResults::where(['betlist_id' => $exist->id])->update(['game_result' => $detail]);

                    }

                }

                $arrList = [
                    'sdate' => $sDate,
                    'edate' => $eDate,
                    'add' => $arrAdd,
                    'update' => $arrUpdate
                ];

                 print_r($arrList);

            } // End foreach boards

        }

        /**
         * Sexy Report
         */
        elseif($game == "sexy"){

            foreach ($boards as $board){

                $key = json_decode($board['api_code'], true);
                $api = new SEXY($key);

                $arrList = [];
                $arrAdd = [];
                $arrUpdate = [];
                $bets = $api->getBetItemByUpdateDate();

//                print_r($bets['transactions']);
//                continue;

                if($bets['status'] != 0000){
                    continue;
                }

                if(count($bets['transactions']) == 0){
                    continue;
                }

                foreach ($bets['transactions'] as $item) {
                    $username = Username::getUsernameByUser($item['userId']);

                    $member_id = $username['member_id'];
                    $agent_id = $username['agent_id'];
                    $username_id = $username['username_id'];
                    $board_game_id = $username['game_id'];

                    // Check Exist
                    $exist = Betlists::where('board_game_id', $board_game_id)
                        ->where('bet_id', $item['ID'])
                        ->first();

                    if(empty($exist)) {

                        // get member comm
                        $comm = MembersCommissions::getMemberCommissions($member_id);

                        $gType = explode("-", $item['platformTxId']);
                        // Check Game Type
                        $gameType = GamesTypes::getTypeByCode($gType[0], $username['game_id']);
                        $game_type_id = $gameType['id'];
    //                    print_r($gameType);
    //                    continue;

                        // Check taking
                        $super_taking = $gameType['taking'] / 100;
                        $company_taking = 1 - $super_taking;
                        $agent_taking = 0;

                        // check commission
                        $commission = 0;
                        $commission_amount = 0;
                        if (isset($comm[$board_game_id][$game_type_id])) {
                            $commission = $comm[$board_game_id][$game_type_id] / 100; // b/c show on system 0.00 - 1.00
                            $commission_amount = $item['turnover'] * $commission;
                        }

                        $result_amount = $item['realWinAmount'] - $item['realBetAmount'];

                        $result_winloss = $result_amount;
                        $result_rolling = $item['turnover']; // use for calculate commission
                        // check taking
                        $taking_winloss = $result_amount * -1;
                        $taking_commission = $commission_amount * -1;

                        // agent
                        $agent_winloss = 0;
                        $agent_amount = 0;
                        $agent_commission = 0;

                        // super taking amount and comm
                        $super_winloss = $taking_winloss * $super_taking;
                        $super_amount = $result_rolling * $super_taking;
                        $super_commission = $taking_commission; // super response 100%

                        // company
                        $company_winloss = $taking_winloss - $super_winloss;
                        $company_amount = $result_rolling - $super_amount;
                        $company_commission = 0;

                        $bet_time = date('Y-m-d H:i:s', strtotime($item['txTime']));
                        $bet_time = date('Y-m-d H:i:s', strtotime("1 hour", strtotime($bet_time)));

                        $payout_time = date('Y-m-d H:i:s', strtotime($item['updateTime']));
                        $payout_time = date('Y-m-d H:i:s', strtotime("1 hour", strtotime($payout_time)));

                        $work_time = date('Y-m-d H:i:s', strtotime($item['updateTime']));
                        $work_time = date('Y-m-d H:i:s', strtotime("1 hour", strtotime($work_time)));

                        $detail = json_encode($item, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

                        $info = json_decode($item['gameInfo'], true);

                        $arrItem = [
                            'member_id' => $member_id,
                            'agent_id' => $agent_id,
                            'username' => $item['userId'],
                            'username_id' => $username_id,
                            'board_game_id' => $board_game_id,
                            'game_type_id' => $game_type_id,
                            'bet_id' => $item['ID'],
                            'bet_time' => $bet_time,
                            'payout_time' => $payout_time,
                            'work_time' => $work_time,
                            'match_time' => $bet_time,
                            'game_id' => $item['platformTxId'],
                            'host_id' => $item['gameCode'],
                            'host_name' => null,
                            'game_type' => $item['gameType'],
                            'set' => null,
                            'round' => $item['roundId'],
                            'bet_type' => $item['betType'],
                            'bet_amount' => $item['realBetAmount'],
                            'rolling' => (string)$item['turnover'],
                            'detail' => $item['gameInfo'],
                            'result_amount' => (string)$result_winloss,
                            'balance' => null,
                            'state' => $info['status'],
                            'commission' => (string)$commission,
                            'commission_amount' => (string)$commission_amount,

                            'agent_taking' => (string)$agent_taking,
                            'agent_winloss' => (string)$agent_winloss,
                            'agent_amount' => (string)$agent_amount,
                            'agent_commission' => (string)$agent_commission,

                            'super_taking' => (string)$super_taking,
                            'super_winloss' => (string)$super_winloss,
                            'super_amount' => (string)$super_amount,
                            'super_commission' => (string)$super_commission,

                            'company_taking' => (string)$company_taking,
                            'company_winloss' => (string)$company_winloss,
                            'company_amount' => (string)$company_amount,
                            'company_commission' => (string)$company_commission,
                            'trans_commission_status' => 1,
                        ];


                            $arrAdd[] = $item;

                            $entity = $repository->createEntity($arrItem, \App::make($this->entityClass));

                            $arrResult = [
                                'betlist_id' => $entity->id,
                                'game_result' => $detail
                            ];
                            $repository->createEntity($arrResult, \App::make(BetlistsResults::class));
                        }

                    /**
                     * Update
                     */
                    else{

                        $arrUpdate[] = $item;

                    }

                }

                $arrList = [
                    'add' => $arrAdd,
                    'update' => $arrUpdate
                ];

//                print_r($arrList);

            } // End foreach boards

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
         * Pussy
         */
        elseif($game == "pussy"){

            $api = new PUSSY();
            return $api->betLogTmp($boards);

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