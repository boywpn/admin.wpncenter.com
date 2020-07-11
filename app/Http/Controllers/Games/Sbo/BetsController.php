<?php

namespace App\Http\Controllers\Games\Sbo;

use Illuminate\Http\Request;
use Modules\Api\Http\Controllers\Game\TransferApiController;
use Modules\Core\Boards\Entities\Boards;
use Modules\Core\Games\Entities\GamesTypes;
use Modules\Core\Username\Entities\Username;
use Modules\Member\Members\Entities\MembersCommissions;
use Modules\Report\Betlists\Entities\Betlists;
use Modules\Report\Betlists\Entities\BetlistsResults;
use Modules\Report\Betlists\Entities\BetlistsTmp;
use Modules\Report\Commission\Entities\Commission;

class BetsController extends SboController
{

    public function getBetItems($Portfolio, $debug = false)
    {

        // gtm -4

        $date = date('Y-m-d H:i:s');
        $sDate = date('Y-m-d\TH:i:s', strtotime('-665 minutes', strtotime($date)));
        $eDate = date('Y-m-d\TH:i:s', strtotime('-660 minutes', strtotime($date)));

        $setParam = [
            "StartDate" => $sDate,
            "EndDate" => $eDate,
            "Portfolio" => $Portfolio
        ];
        $param = $this->setParam($setParam, 'web-root/restricted/report/v2/get-bet-list-by-modify-date.aspx');

        $response = $this->push();
        $response = json_decode($response, true);

        if($debug) {
            return compact('param', 'response');
        }

        return $response;
    }

    public function getTransactions($get, $debug = false)
    {

        // gtm -4

        $date = date('Y-m-d H:i:s');
        $sDate = date('Y-m-d\TH:i:s', strtotime('-665 minutes', strtotime($date)));
        $eDate = date('Y-m-d\TH:i:s', strtotime('-660 minutes', strtotime($date)));

        $setParam = [
            "Username" => $get['username'],
            "StartDate" => "2020-06-23T11:00:00",
            "EndDate" => "2020-06-23T11:30:00",
            "Portfolio" => $get['portfolio']
        ];
        $param = $this->setParam($setParam, 'web-root/restricted/report/get-bet-list-by-transaction-date.aspx');

        $response = $this->push();
        $response = json_decode($response, true);

        if($debug) {
            return compact('param', 'response');
        }

        return $response;
    }

    public function getBetItemsByDate($Portfolio, $date, $debug = false)
    {

        // gtm -4
        $sDate = date('Y-m-d\T00:00:00', strtotime($date));
        $eDate = date('Y-m-d\T23:59:59', strtotime($date));

        $setParam = [
            "StartDate" => $sDate,
            "EndDate" => $eDate,
            "Portfolio" => $Portfolio
        ];
        $param = $this->setParam($setParam, 'web-root/restricted/report/v2/get-bet-list-by-modify-date.aspx');

        $response = $this->push();
        $response = json_decode($response, true);

        $response['byDate'] = $date;

        if($debug) {
            return compact('param', 'response');
        }

        return $response;
    }

    public function betLogTmp($boards, $Portfolio, $byDate = null){

        $this->entityClass = BetlistsTmp::class;
        $repository = $this->getRepository();

        $arrList = [];
        $arrCount = [];

        foreach ($boards as $board){

            $key = json_decode($board['api_code'], true);
            $this->setKey($key);

            $arrAdd = [];
            $arrUpdate = [];
            $board_game_id = $board['game_id'];

            if(empty($board['lastkey'])){
                $lastKey = 0;
            }else{
                $lastKey = $board['lastkey'];
            }

            $start = $lastKey;

            if(empty($byDate)) {
                $bets = $this->getBetItems($Portfolio);
            }else{
                $bets = $this->getBetItemsByDate($Portfolio, $byDate);
//                print_r($bets);
//                continue;
            }
//            print_r($bets);
//            continue;

            if($bets['error']['id'] != 0){
                continue;
            }

            if(count($bets['result']) == 0){
                continue;
            }

            $items = $bets['result'];

            if(count($items) == 0){
                $items = [];
            }

            foreach ($items as $item) {

                $hash = $board_game_id.$item['refNo'].$item['modifyDate'];
                $md5 = md5($hash);

                $exist = BetlistsTmp::where('hash', $md5)->first();

                if(empty($exist)) {

                    $arrTmp = [
                        'game_id' => $board_game_id,
                        'hash' => $md5,
                        'bet_ref' => $item['refNo'],
                        'agent_ref' => $key['agent'],
                        'data' => json_encode($item, JSON_UNESCAPED_UNICODE),
                    ];
                    $entity = $repository->createEntity($arrTmp, \App::make(BetlistsTmp::class));

                    $arrAdd[] = $entity;

                    $lastKey = $item['refNo'];

                }
                /**
                 * Update
                 */
                else{

                    $arrUpdate[] = $item;

//                    BetlistsTmp::where(['id' => $exist->id])->update(['status' => 10, 'request_at' => date('Y-m-d H:i:s')]);

                }

            }

            $arrList[$key['agent']] = [
                'add' => $arrAdd,
                'update' => $arrUpdate
            ];

            $arrCount[$key['agent']] = [
                'add' => count($arrAdd),
                'update' => count($arrUpdate),
                'time' => date('Y-m-d H:i:s'),
                'startKey' => $start,
                'lastKey' => $lastKey
            ];

        } // End foreach boards

        return compact('byDate','arrCount', 'arrList');

    }

    public function betFixComm($game_id, $game_type_id){

        return false;

        $this->entityClass = Betlists::class;
        $repository = $this->getRepository();

        $arrList = [];
        $arrAdd = [];
        $arrUpdate = [];
        $arrCount = [];

        $bets = Betlists::where('board_game_id', $game_id)
            ->where('game_type_id', $game_type_id)
            ->where('commission', '0')
            ->orderBy('id', 'ASC')
            ->limit(1000)
            ->get();

        foreach ($bets->toArray() as $item) {

            $user = $item['username'];
            $username = Username::getUsernameByUser($user);

            $member_id = $username['member_id'];
            $agent_id = $username['agent_id'];
            $username_id = $username['username_id'];
            $board_game_id = $username['game_id'];

            // get member comm
            $comm = MembersCommissions::getMemberCommissions($member_id);

            // Check Game Type
            $gameType = GamesTypes::find($game_type_id);
            $game_type_id = $gameType['id'];

            // Check taking
            $super_taking = $gameType['taking'] / 100;
            $company_taking = 1 - $super_taking;
            $agent_taking = 0;

            // check commission
            $commission = 0;
            $commission_amount = 0;

            $result_commission_amount = $item['result_commission_amount'];

            if (isset($comm[$board_game_id][$game_type_id])) {
                $commission = $comm[$board_game_id][$game_type_id] / 100; // b/c show on system 0.00 - 1.00
                $commission_amount = $result_commission_amount * $commission;
            }

            $taking_commission = $commission_amount * -1;

            // agent
            $agent_commission = 0;

            // super taking amount and comm
            $super_commission = $taking_commission; // super response 100%

            // company
            $company_commission = 0;

            $arrItem = [
                'commission' => (string)$commission,
                'commission_amount' => (string)$commission_amount,

                'agent_commission' => (string)$agent_commission,

                'super_commission' => (string)$super_commission,

                'company_commission' => (string)$company_commission,
            ];

            $repository->setupModel($this->entityClass);
            $entity = $repository->find($item['id']);
            $repository->updateEntity($arrItem, $entity);

            $arrList[] = $entity;

        }

        return $arrList;

    }

    public function betFixDate($game_id, $game_type_id){

        return false;

        $this->entityClass = Betlists::class;
        $repository = $this->getRepository();

        $arrList = [];
        $arrAdd = [];
        $arrUpdate = [];
        $arrCount = [];

        $bets = Betlists::where('board_game_id', $game_id)
            ->where('game_type_id', $game_type_id)
            ->orderBy('id', 'ASC')
            ->limit(10000)
            ->get();

        foreach ($bets->toArray() as $item) {

            $detail = json_decode($item['detail'], true);

            if(isset($detail['productType'])){

                $arrData = [
                    'game_type_id' => 56,
                    'game_type' => 'virtualsports'
                ];

                $repository->setupModel($this->entityClass);
                $entity = $repository->find($item['id']);
                $repository->updateEntity($arrData, $entity);

                $arrList[] = [
                    'before' => $item,
                    'last' => $entity
                ];

            }

        }

        return $arrList;

    }

    public function betLogSave($game_id, $limit = 500, $status = null){

        $this->entityClass = Betlists::class;
        $repository = $this->getRepository();

        $arrList = [
            'sport' => [],
            'casino' => [],
            'games' => [],
        ];
        $arrAdd = [];
        $arrUpdate = [];
        $arrCount = [];

        $bets = BetlistsTmp::where('game_id', $game_id)
//            ->whereNull('status')
            ->when((!empty($status)), function($query) use ($status){
                $query->where('status', $status);
            })
            ->when((empty($status)), function($query) use ($status){
                $query->whereNull('status');
            })
            ->orderBy('id', 'ASC')
            ->limit($limit)
            ->get();

//        return $bets;

        foreach ($bets as $items) {

            $id = $items->id;
            $item = json_decode($items->data, true);

            if(isset($item['subBet'])){
                // $arrList['sport'][] = $item;
                $report = $this->betLogSaveBooks($id, $item);
                $arrList['sport'][] = [
                    'report' => $report,
                    'item' => $item
                ];
            }else{

                if(isset($item['gpId'])){
                    $report = $this->betLogSaveCasino($id, $item);
                    $arrList['casino'][] = [
                        'report' => $report,
                        'item' => $item
                    ];
                }else{
                    // $arrList['games'][] = $item;
                    $arr = [];
                    foreach ($item as $k => $d){
                        $arr[strtolower($k)] = $d;
                    }
                    $item = $arr;
                    $report = $this->betLogSaveGames($id, $item);
                    $arrList['games'][] = [
                        'report' => $report,
                        'item' => $item
                    ];
                }
            }

        }

        $arrCount = [
            'sport' => count($arrList['sport']),
            'casino' => count($arrList['casino']),
            'games' => count($arrList['games'])
        ];

        return compact('arrCount', 'arrList');

    }

    public function betLogSaveGames($id, $item){

        $this->entityClass = Betlists::class;
        $repository = $this->getRepository();

        $arrList = [];
        $arrAdd = [];
        $arrUpdate = [];

        $user = $item['username'];
        $username = Username::getUsernameByUser($user);

        $member_id = $username['member_id'];
        $agent_id = $username['agent_id'];
        $username_id = $username['username_id'];
        $board_game_id = $username['game_id'];

        $hash = md5($board_game_id.$item['refno']);

        // get member comm
        $comm = MembersCommissions::getMemberCommissions($member_id);

        // Check Game Type
        if(isset($item['tablename'])){
            $game_type = 'livecasino'; // For Live Casino
        }else{
            $game_type = 'sbogames';
        }
        $gameType = GamesTypes::getTypeByCode($game_type, $board_game_id);

        // If Type not exist
        if(!$gameType){
            BetlistsTmp::where(['id' => $id])->update(['status' => 9, 'request_at' => date('Y-m-d H:i:s')]);
            return false;
        }

        $game_type_id = $gameType['id'];

//        return compact('comm', 'gameType', 'game_type_id', 'game_type', 'board_game_id');

        // Check taking
        $super_taking = $gameType['taking'] / 100;
        $company_taking = 1 - $super_taking;
        $agent_taking = 0;

        // check commission
        $commission = 0;
        $commission_amount = 0;

        $result_amount = $item['winlost'];
        $validAmount = $item['winlost'];
        $result_commission_amount = $item['turnover'];

        if (isset($comm[$board_game_id][$game_type_id])) {
            $commission = $comm[$board_game_id][$game_type_id] / 100; // b/c show on system 0.00 - 1.00
            $commission_amount = $result_commission_amount * $commission;
        }

        $result_winloss = $result_amount;
        $result_rolling = $validAmount; // use for calculate commission
        // check taking
        $taking_winloss = ($result_amount != 0) ? $result_amount * -1 : 0;
        $taking_commission = ($commission_amount > 0) ? $commission_amount * -1 : 0;

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

        $arrItem = [
            'member_id' => $member_id,
            'agent_id' => $agent_id,
            'username' => $item['username'],
            'username_id' => $username_id,
            'board_game_id' => $board_game_id,
            'game_type_id' => $game_type_id,
            'bet_id' => $item['refno'],
            'hash' => $hash,
            'bet_time' => $item['ordertime'],
            'payout_time' => $item['modifydate'],
            'work_time' => $item['ordertime'],
            'match_time' => $item['ordertime'],
            'game_id' => $item['gameid'],
            'host_id' => null,
            'host_name' => (isset($item['tablename'])) ? $item['tablename'] : null,
            'game_type' => (string)$game_type,
            'set' => null,
            'round' => null,
            'bet_type' => ((isset($item['gamename'])) ? $item['gamename']." - " : "").$item['producttype'],
            'bet_amount' => (string)$item['stake'],
            'turnover' => (string)$item['stake'],
            'rolling' => (string)$validAmount,
//            'detail' => $detail,
            'result_amount' => (string)$result_winloss,
            'result_commission_amount' => (string)$result_commission_amount,
            'balance' => null,
            'state' => (string)$item['status'],
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

            /**
             * Update Tmp
             */
            BetlistsTmp::where(['id' => $id])->update(['status' => 1, 'request_at' => date('Y-m-d H:i:s'), 'betlist_id' => $entity->id]);

        }
        /**
         * Update
         */
        else{

            $arrUpdate[] = $exist;

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
//            BetlistsResults::where(['betlist_id' => $exist->id])->update(['game_result' => $detail]);

            /**
             * Update Tmp
             */
            BetlistsTmp::where(['id' => $id])->update(['status' => 2, 'request_at' => date('Y-m-d H:i:s'), 'betlist_id' => $exist->id]);

        }

        $arrCount = [
            'add' => count($arrAdd),
            'update' => count($arrUpdate)
        ];

        $arrList = [
            'add' => $arrAdd,
            'update' => $arrUpdate
        ];

        return compact('arrCount', 'arrList');

    }

    public function betLogSaveCasino($id, $item){

        $this->entityClass = Betlists::class;
        $repository = $this->getRepository();

        $arrList = [];
        $arrAdd = [];
        $arrUpdate = [];

        $user = $item['username'];
        $username = Username::getUsernameByUser($user);

        $member_id = $username['member_id'];
        $agent_id = $username['agent_id'];
        $username_id = $username['username_id'];
        $board_game_id = $username['game_id'];

        $hash = md5($board_game_id.$item['refNo']);

        // get member comm
        $comm = MembersCommissions::getMemberCommissions($member_id);

        // Check Game Type
        $game_type = $item['gpId'];
        $gameType = GamesTypes::getTypeByCode($game_type, $board_game_id);

        // If Type not exist
        if(!$gameType){
            BetlistsTmp::where(['id' => $id])->update(['status' => 8, 'request_at' => date('Y-m-d H:i:s')]);
            return false;
        }

        $game_type_id = $gameType['id'];

//            return compact('comm', 'gameType', 'game_type_id', 'game_type', 'board_game_id');

        // Check taking
        $super_taking = $gameType['taking'] / 100;
        $company_taking = 1 - $super_taking;
        $agent_taking = 0;

        // check commission
        $commission = 0;
        $commission_amount = 0;

        $result_amount = $item['winLost'];
        $validAmount = $item['turnoverStake'];
        $result_commission_amount = $item['turnoverStake'];

        if (isset($comm[$board_game_id][$game_type_id])) {
            $commission = $comm[$board_game_id][$game_type_id] / 100; // b/c show on system 0.00 - 1.00
            $commission_amount = $result_commission_amount * $commission;
        }

        $result_winloss = $result_amount;
        $result_rolling = $validAmount; // use for calculate commission
        // check taking
        $taking_winloss = ($result_amount != 0) ? $result_amount * -1 : 0;
        $taking_commission = ($commission_amount > 0) ? $commission_amount * -1 : 0;

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

        $arrItem = [
            'member_id' => $member_id,
            'agent_id' => $agent_id,
            'username' => $item['username'],
            'username_id' => $username_id,
            'board_game_id' => $board_game_id,
            'game_type_id' => $game_type_id,
            'bet_id' => $item['refNo'],
            'hash' => $hash,
            'bet_time' => $item['orderTime'],
            'payout_time' => $item['modifyDate'],
            'work_time' => (isset($item['winLostDate'])) ? $item['winLostDate'] : $item['orderTime'],
            'match_time' => $item['orderTime'],
            'game_id' => $item['gamePeriodId'],
            'host_id' => null,
            'host_name' => null,
            'game_type' => (string)$game_type,
            'set' => null,
            'round' => $item['gameRoundId'],
            'bet_type' => $item['gameType'],
            'bet_amount' => (string)$item['stake'],
            'turnover' => (string)$item['stake'],
            'result_commission_amount' => (string)$result_commission_amount,
            'rolling' => (string)$validAmount,
//            'detail' => $detail,
            'result_amount' => (string)$result_winloss,
            'balance' => null,
            'state' => (string)$item['status'],
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

            /**
             * Update Tmp
             */
            BetlistsTmp::where(['id' => $id])->update(['status' => 1, 'request_at' => date('Y-m-d H:i:s'), 'betlist_id' => $entity->id]);

        }
        /**
         * Update
         */
        else{

            $arrUpdate[] = $exist;

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
//            BetlistsResults::where(['betlist_id' => $exist->id])->update(['game_result' => $detail]);

            /**
             * Update Tmp
             */
            BetlistsTmp::where(['id' => $id])->update(['status' => 2, 'request_at' => date('Y-m-d H:i:s'), 'betlist_id' => $exist->id]);

        }

        $arrCount = [
            'add' => count($arrAdd),
            'update' => count($arrUpdate)
        ];

        $arrList = [
            'add' => $arrAdd,
            'update' => $arrUpdate
        ];

        return compact('arrCount', 'arrList');

    }

    public function betLogSaveBooks($id, $item){

        $this->entityClass = Betlists::class;
        $repository = $this->getRepository();

        $arrList = [];
        $arrAdd = [];
        $arrUpdate = [];

        $user = $item['username'];
        $username = Username::getUsernameByUser($user);

        $member_id = $username['member_id'];
        $agent_id = $username['agent_id'];
        $username_id = $username['username_id'];
        $board_game_id = $username['game_id'];

        // get member comm
        $comm = MembersCommissions::getMemberCommissions($member_id);

        // Check Game Type
        $game_type = "sportsbook";
//        if (strpos($item['refNo'], 'V') !== false) {
//            $game_type = "virtualsports";
//        }
        if(isset($item['productType'])){
            $game_type = "virtualsports";
        }
        $gameType = GamesTypes::getTypeByCode($game_type, $board_game_id);
        $game_type_id = $gameType['id'];

//        return compact('comm', 'gameType', 'game_type_id', 'game_type', 'board_game_id');

        // Check taking
        $super_taking = $gameType['taking'] / 100;
        $company_taking = 1 - $super_taking;
        $agent_taking = 0;

        // check commission
        $commission = 0;
        $commission_amount = 0;

        $result_amount = $item['winLost'];
        $validAmount = ($result_amount >= 0) ? $result_amount : $result_amount * -1;

        $result_commission_amount = 0;

        if($game_type == "sportsbook") {
            $result_commission_amount = ($item['winLost'] != 0) ? $item['stake'] : 0;

            //if winLostHalf
            if ($item['isHalfWonLose'] == true) {
                $result_commission_amount = $result_commission_amount / 2;
            }
        }

        if (isset($comm[$board_game_id][$game_type_id])) {
            $commission = $comm[$board_game_id][$game_type_id] / 100; // b/c show on system 0.00 - 1.00
            $commission_amount = $result_commission_amount * $commission;
        }

        $result_winloss = $result_amount;
        $result_rolling = $validAmount; // use for calculate commission
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

        $hash = md5($board_game_id.$item['refNo']);

        $arrItem = [
            'member_id' => $member_id,
            'agent_id' => $agent_id,
            'username' => $item['username'],
            'username_id' => $username_id,
            'board_game_id' => $board_game_id,
            'game_type_id' => $game_type_id,
            'bet_id' => $item['refNo'],
            'hash' => $hash,
            'bet_time' => $item['orderTime'],
            'payout_time' => $item['modifyDate'],
            'work_time' => (isset($item['winLostDate'])) ? $item['winLostDate'] : $item['orderTime'],
            'match_time' => $item['orderTime'],
            'game_id' => null,
            'host_id' => null,
            'host_name' => null,
            'game_type' => $game_type,
            'set' => null,
            'round' => null,
            'bet_type' => (isset($item['sportsType'])) ? $item['sportsType'] : $item['productType'],
            'bet_amount' => (string)$item['stake'],
            'turnover' => (string)$item['turnover'],
            'rolling' => (string)$validAmount,
//            'detail' => $detail,
            'result_amount' => (string)$result_winloss,
            'result_commission_amount' => (string)$result_commission_amount,
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

        $giveComm = 1;

        if(empty($exist)) {

            $arrAdd[] = $arrItem;

            $entity = $repository->createEntity($arrItem, \App::make($this->entityClass));

            $arrResult = [
                'betlist_id' => $entity->id,
                'game_result' => $detail
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

            $repository->setupModel($this->entityClass);
            $entity = $repository->find($exist->id);
            $repository->updateEntity($arrItem, $entity);

            $arrUpdate[] = $entity;

            $arrResult = [
                'betlist_id' => $exist->id,
                'game_result' => $detail
            ];
            $repository->createEntity($arrResult, \App::make(BetlistsResults::class));

            /**
             * Update Result
             */
//            BetlistsResults::where(['betlist_id' => $exist->id])->update(['game_result' => $detail]);

            /**
             * Update Tmp
             */
            BetlistsTmp::where(['id' => $id])->update(['status' => 2, 'request_at' => date('Y-m-d H:i:s'), 'betlist_id' => $exist->id]);

            if(in_array($exist->status, ['lose', 'won'])){
                $giveComm = 2;
            }

        }

        // Commission
        $arrComm = [];
        if(in_array($item['status'], ['lose', 'won']) && $giveComm == 1 && $game_type == "sportsbook"){

            $resComm = $this->giveComm($entity);
            $arrComm[] = $resComm;

        }

        $arrCount = [
            'add' => count($arrAdd),
            'update' => count($arrUpdate),
            'givecomm' => count($arrComm)
        ];

        $arrList = [
            'add' => $arrAdd,
            'update' => $arrUpdate,
            'givecomm' => $arrComm,
            'item' => $item
        ];

        return compact('arrCount', 'arrList');

    }

    public function giveComm($betlist){

        $exist = Commission::where('betlist_id', $betlist->id)->first();
        if($exist) {
            return "Exist Commission";
        }

        $this->entityClass = Betlists::class;

        $repository = $this->getRepository();
        $repository->setupModel(Commission::class);

        $date = date('Y-m-d H:i:s');

        $arrData = [
            'betlist_id' => $betlist->id,
            'member_id' => $betlist->member_id,
            'username_id' => $betlist->username_id,
            'username' => $betlist->username,
            'game_id' => $betlist->board_game_id,
            'type_id' => $betlist->game_type_id,
            'date' => $date,
            'pay_time' => $date,
            'comm_value' => $betlist->result_commission_amount,
            'comm_percent' => $betlist->commission,
            'amount' => round($betlist->super_commission, 2) * -1,
            'status' => 1,
        ];
        $entity = $repository->createEntity($arrData, \App::make(Commission::class));

        $arrTrans = [
            'comm_id' => $entity->id,
            'action' => 'transfer',
            'orderid' => 'comm_auto',
            'custid' => $betlist->username,
            'type' => 'transfer',
            'amount' => (string)round($entity->amount, 2),
            'staffid' => 1,
            'from' => 'api_auto_sbo_commission',
            'stateid' => $entity->betlist_id,
            'local_ip' => get_client_ip(),
            'auto' => true,
        ];

        $arrRequest = [
            'request_time' => $date,
            'request_data' => json_encode($arrTrans, JSON_UNESCAPED_UNICODE),
        ];

        $repository->updateEntity($arrRequest, $entity);

        $trans = new TransferApiController();
        $response = $trans->transfer($arrTrans);

        $arrResponse = [
            'response_time' => date('Y-m-d H:i:s'),
            'response_data' => json_encode($response, JSON_UNESCAPED_UNICODE),
            'ref_code' => ($response['responseStatus']['code'] == 200) ? $response['responseDetails']['order_api_id'] : null,
            'status' => ($response['responseStatus']['code'] == 200) ? 2 : 3,
        ];
        $entity = $repository->updateEntity($arrResponse, $entity);

        /**
         * Update Result
         */
        Betlists::where(['id' => $betlist->id])->update(['trans_commission_status' => 2, 'trans_commission_id' => $entity->id, 'trans_commission_at' => date('Y-m-d H:i:s')]);

        return $entity;

    }

    public function fixCommByUser($username_id){

        $this->entityClass = Commission::class;

        $repository = $this->getRepository();
        $repository->setupModel(Commission::class);

        $date = date('Y-m-d H:i:s');

        $user = Username::findOrFail($username_id);

        $entities = Commission::where('username_id', $username_id)
            ->where('status', 3)
            ->limit(1)
            ->get();

        $arrRes = [];
        foreach ($entities as $entity) {
            $arrTrans = [
                'comm_id' => $entity->id,
                'action' => 'transfer',
                'orderid' => 'comm_auto',
                'custid' => $user->username,
                'type' => 'transfer',
                'amount' => (string)round($entity->amount, 2),
                'staffid' => 1,
                'from' => 'api_auto_sbo_commission',
                'stateid' => $entity->betlist_id,
                'local_ip' => get_client_ip(),
                'auto' => true,
            ];

            $arrRequest = [
                'request_time' => $date,
                'request_data' => json_encode($arrTrans, JSON_UNESCAPED_UNICODE),
            ];

            $repository->updateEntity($arrRequest, $entity);

            $trans = new TransferApiController();
            $response = $trans->transfer($arrTrans);

            $arrResponse = [
                'response_time' => date('Y-m-d H:i:s'),
                'response_data' => json_encode($response, JSON_UNESCAPED_UNICODE),
                'response_ref' => ($response['responseStatus']['code'] == 200) ? $response['responseDetails']['order_api_id'] : null,
                'status' => ($response['responseStatus']['code'] == 200) ? 2 : 3,
            ];
            $entity = $repository->updateEntity($arrResponse, $entity);

            /**
             * Update Result
             */
            Betlists::where(['id' => $entity->betlist_id])->update(['trans_commission_status' => 2, 'trans_commission_id' => $entity->id, 'trans_commission_at' => date('Y-m-d H:i:s')]);

            $arrRes[] = compact('arrTrans', 'arrResponse');

//            $arrRes[] = compact('arrTrans');
        }

        return $arrRes;

    }

}
