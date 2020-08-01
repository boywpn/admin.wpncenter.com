<?php

namespace App\Http\Controllers\Games\Ibc;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Api\Http\Controllers\Game\TransferApiController;
use Modules\Core\Boards\Entities\Boards;
use Modules\Core\Games\Entities\GamesTypes;
use Modules\Core\Username\Entities\Username;
use Modules\Member\Members\Entities\MembersCommissions;
use Modules\Report\Betlists\Entities\Betlists;
use Modules\Report\Betlists\Entities\BetlistsResults;
use Modules\Report\Betlists\Entities\BetlistsTmp;
use Modules\Report\Betlists\Entities\BetlistsTmpBup;
use Modules\Report\Commission\Entities\Commission;

class BetsController extends IbcController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function getBetItems($key = 0, $debug = false)
    {

        $setParam = [
            'keyOrdate' => 'key'
        ];

        $setSuffix = [
            'from' => date("Y-m-d\TH:i:s", strtotime(genDate('-685 minutes'))),
            'to' => date("Y-m-d\TH:i:s", strtotime(genDate('-660 minutes'))),
            'versionkey' => $key
        ];

        $param = $this->setParam($setParam, 'repullBettingHistoryApiClient.ashx', ['providercode' => 'IB'], $setSuffix, $this->apiReportUrl);

        $response = $this->push(false);

        if($debug) {
            return compact('param', 'response');
        }

        $response = json_decode($response, true);

        return $response;
    }

    public function getBetItemsByKey($lastKey, $debug = false)
    {

        $setParam = [];
        $setPrefix = [];
        $setSuffix = [
            'versionkey' => $lastKey
        ];

        $param = $this->setParam($setParam, 'fetchbykey.aspx', $setPrefix, $setSuffix, $this->apiReportUrl);

        $response = $this->push(false);

        if($debug) {
            return compact('param', 'response');
        }

        $response = json_decode($response, true);

        return $response;
    }

    public function betLogTmpByTime($boards){

        $this->entityClass = BetlistsTmp::class;
        $repository = $this->getRepository();

        $arrList = [];
        $arrCount = [];

        foreach ($boards as $board){

            $key = json_decode($board['api_code'], true);
            $this->setKey($key);

            $arrList = [];
            $arrAdd = [];
            $arrUpdate = [];
            $board_game_id = 21;

//            $last_tmp = BetlistsTmp::where('game_id', $board_game_id)
//                ->select('id', 'board_id', 'last_key')
//                ->orderBy('version_key', 'DESC')
//                ->first();
//
//            if(empty($last_tmp)){
//                $lastKey = 0;
//            }else{
//                $lastKey = $last_tmp->last_key;
//            }

            if(empty($board['lastkey'])){
                $lastKey = 0;
            }else{
                $lastKey = $board['lastkey'];
            }

            $bets = $this->getBetItems($lastKey);

//            print_r($bets);
//            continue;

            if($bets['errCode'] != 0){
                continue;
            }

            $items = json_decode($bets['record'], true);

//            print_r($items);
//            continue;

            if(!is_array($items)){
                continue;
            }

            if(count($items['Data']) == 0){
                continue;
            }

//                print_r($items);

            foreach ($items['Data'] as $item) {

                $user = str_replace($key['agent'], "", $item['PlayerName']);

                $hash = $board_game_id.$item['TransId'].$item['VersionKey'];
                $md5 = md5($hash);

                $exist = BetlistsTmp::where('hash', $md5)->first();

                if(empty($exist)) {

                    $arrTmp = [
                        'game_id' => $board_game_id,
                        'hash' => $md5,
                        'bet_ref' => $item['TransId'],
                        'last_key' => $item['LastVersionKey'],
                        'version_key' => $item['VersionKey'],
                        'data' => json_encode($item, JSON_UNESCAPED_UNICODE),
                    ];
                    $entity = $repository->createEntity($arrTmp, \App::make(BetlistsTmp::class));

                    $arrAdd[] = $item;

                }
                /**
                 * Update
                 */
                else{

                    $arrUpdate[] = $item;

                }

            }

            /**
             * Update Last Key
             */
            Boards::where(['ref' => $board['ref'], 'game_id' => $board_game_id])->update(['lastkey' => $items['LastVersionKey']]);

            $arrList[$key['agent']] = [
                'add' => $arrAdd,
                'update' => $arrUpdate,
                'lastKey' => $items['LastVersionKey']
            ];

            $arrCount[$key['agent']] = [
                'add' => count($arrAdd),
                'update' => count($arrUpdate),
                'time' => date('Y-m-d H:i:s'),
                'startKey' => $lastKey,
                'lastKey' => $items['LastVersionKey']
            ];

        } // End foreach boards

        return compact('arrCount', 'arrList');

    }

    public function betLogTmp($boards){

        $this->entityClass = BetlistsTmp::class;
        $repository = $this->getRepository();

        $arrList = [];
        $arrCount = [];

        foreach ($boards as $board){

            $key = json_decode($board['api_code'], true);
            $this->setKey($key);

            $arrList = [];
            $arrAdd = [];
            $arrUpdate = [];
            $board_game_id = $board['game_id'];

//            $last_tmp = BetlistsTmp::where('game_id', $board_game_id)
//                ->select('id', 'board_id', 'last_key')
//                ->orderBy('version_key', 'DESC')
//                ->first();

            if(empty($board['lastkey'])){
                $lastKey = 0;
            }else{
                $lastKey = $board['lastkey'];
            }

            $bets = $this->getBetItemsByKey($lastKey);

//            print_r($bets);
//            continue;

            if($bets['errCode'] != 0){
                continue;
            }

            $items = json_decode($bets['result'], true);

//            print_r($items);
//            continue;

            foreach ($items as $item) {

                $hash = $board_game_id.$item['id'].$item['ref_no'].$item['site'];
                $md5 = md5($hash);

                $exist = BetlistsTmp::where('hash', $md5)->first();

                if(empty($exist)) {

                    $arrTmp = [
                        'game_id' => $board_game_id,
                        'hash' => $md5,
                        'bet_id' => $item['id'],
                        'bet_ref' => $item['site']."_".$item['ref_no'],
                        'data' => json_encode($item, JSON_UNESCAPED_UNICODE),
                    ];
                    $entity = $repository->createEntity($arrTmp, \App::make(BetlistsTmp::class));

                    $arrAdd[] = $entity;

                }
                /**
                 * Update
                 */
                else{

                    $arrUpdate[] = $item;

                }

            }

            /**
             * Update Last Key
             */
            Boards::where(['ref' => $board['ref'], 'game_id' => $board_game_id])->update(['lastkey' => $bets['lastversionkey']]);

            $arrList[$key['agent']] = [
                'add' => $arrAdd,
                'update' => $arrUpdate,
                'lastKey' => $bets['lastversionkey']
            ];

            $arrCount[$key['agent']] = [
                'add' => count($arrAdd),
                'update' => count($arrUpdate),
                'time' => date('Y-m-d H:i:s'),
                'startKey' => $lastKey,
                'lastKey' => $bets['lastversionkey']
            ];

        } // End foreach boards

        return compact('arrCount', 'arrList');

    }

    public function betLogSave($game_id){

        $this->entityClass = Betlists::class;
        $repository = $this->getRepository();

        $arrList = [];
        $arrAdd = [];
        $arrUpdate = [];

        $bets = BetlistsTmp::where('game_id', $game_id)
            ->where('status', 0)
            ->orderBy('id', 'ASC')
            ->limit(500)
            ->get();

        foreach ($bets as $items) {

            $id = $items->id;
            $bet_id = $items->bet_id;
            $item = json_decode($items->data, true);

//            print_r($item);
//            continue;

            $user = $item['member'];
            $username = Username::getUsernameByUser($user);
//            print_r($username);
//            continue;

            $member_id = $username['member_id'];
            $agent_id = $username['agent_id'];
            $username_id = $username['username_id'];
            $board_game_id = $username['game_id'];

            // get member comm
            $comm = MembersCommissions::getMemberCommissions($member_id);

            // Check Game Type
            $game_type = $item['product'];
            $gameType = GamesTypes::getTypeByCode($game_type, $board_game_id);
            $game_type_id = $gameType['id'];
//                print_r($gameType);
//                continue;

            // Check taking
            $super_taking = $gameType['taking'] / 100;
            $company_taking = 1 - $super_taking;
            $agent_taking = 0;

            // check commission
            $commission = 0;
            $commission_amount = 0;
            $result_amount = $item['payout'] - $item['bet'];
            $validAmount = ($result_amount >= 0) ? $result_amount : $result_amount * -1;

            if (isset($comm[$board_game_id][$game_type_id])) {
                $commission = $comm[$board_game_id][$game_type_id] / 100; // b/c show on system 0.00 - 1.00
                $commission_amount = $validAmount * $commission;
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

            $hash = md5($item['site'].$item['ref_no']);

            $arrItem = [
                'member_id' => $member_id,
                'agent_id' => $agent_id,
                'username' => $item['member'],
                'username_id' => $username_id,
                'board_game_id' => $board_game_id,
                'game_type_id' => $game_type_id,
                'bet_id' => $item['ref_no'],
                'hash' => $hash,
                'bet_time' => $item['start_time'],
                'payout_time' => $item['end_time'],
                'work_time' => $item['start_time'],
                'match_time' => $item['match_time'],
                'game_id' => $item['id'],
                'host_id' => $item['site'],
                'host_name' => null,
                'game_type' => $game_type,
                'set' => null,
                'round' => null,
                'bet_type' => $item['product'],
                'bet_amount' => $item['bet'],
                'turnover' => $item['turnover'],
                'rolling' => (string)$validAmount,
//                'detail' => $item['bet_detail'],
                'result_amount' => (string)$result_winloss,
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

                /**
                 * Update Tmp
                 */
                BetlistsTmp::where(['id' => $id])->update(['status' => 1, 'request_at' => date('Y-m-d H:i:s'), 'betlist_id' => $entity->id]);

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
//                BetlistsResults::where(['betlist_id' => $exist->id])->update(['game_result' => $detail]);

                /**
                 * Update Tmp
                 */
                BetlistsTmp::where(['id' => $id])->update(['status' => 2, 'request_at' => date('Y-m-d H:i:s'), 'betlist_id' => $exist->id]);

            }

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

    public function betLogSaveTime($game_id){

        $this->entityClass = Betlists::class;
        $repository = $this->getRepository();

        $arrList = [];
        $arrAdd = [];
        $arrUpdate = [];
        $game_type = "SB";

        $bets = BetlistsTmp::where('game_id', $game_id)
            ->whereNull('status')
            ->orderBy('id', 'ASC')
            ->limit(500)
            ->get();

        foreach ($bets as $items) {

            $id = $items->id;
            $bet_id = $items->bet_id;
            $item = json_decode($items->data, true);

            $user = str_replace("sbot", "", $item['PlayerName']);
            $username = Username::getUsernameByUser($user);
//            print_r($username);
//            continue;

            $member_id = $username['member_id'];
            $agent_id = $username['agent_id'];
            $username_id = $username['username_id'];
            $board_game_id = $username['game_id'];

            // get member comm
            $comm = MembersCommissions::getMemberCommissions($member_id);

            // Check Game Type
            $gameType = GamesTypes::getTypeByCode($game_type, $board_game_id);
            $game_type_id = $gameType['id'];
//            print_r($gameType);
//            continue;

            // Check taking
            $super_taking = $gameType['taking'] / 100;
            $company_taking = 1 - $super_taking;
            $agent_taking = 0;

            // check commission
            $commission = 0;
            $commission_amount = 0;
            $result_amount = $item['WinLoseAmount'];
            $validAmount = ($result_amount >= 0) ? $result_amount : $result_amount * -1;
            $result_commission_amount = ($validAmount < $item['Stake']) ? $validAmount : $item['Stake'];

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

            $hash = md5($board_game_id.$item['TransId']);

            $arrItem = [
                'member_id' => $member_id,
                'agent_id' => $agent_id,
                'username' => $user,
                'username_id' => $username_id,
                'board_game_id' => $board_game_id,
                'game_type_id' => $game_type_id,
                'bet_id' => $item['TransId'],
                'hash' => $hash,
                'bet_time' => $item['TransactionTime'],
                'payout_time' => $item['settlement_time'],
                'work_time' => $item['TransactionTime'],
                'match_time' => $item['MatchDatetime'],
                'game_id' => $item['MatchId'],
                'host_id' => $item['ParlayRefNo'],
                'host_name' => null,
                'game_type' => $game_type,
                'set' => null,
                'round' => null,
                'bet_type' => $item['parlay_type'],
                'bet_amount' => (string)$item['Stake'],
                'turnover' => (string)$item['Stake'],
                'rolling' => (string)$validAmount,
//                'detail' => $detail,
                'result_amount' => (string)$result_winloss,
                'result_commission_amount' => (string)$result_commission_amount,
                'balance' => (string)$item['AfterAmount'],
                'state' => $item['TicketStatus'],
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
//                BetlistsResults::where(['betlist_id' => $exist->id])->update(['game_result' => $detail]);

                /**
                 * Update Tmp
                 */
                BetlistsTmp::where(['id' => $id])->update(['status' => 2, 'request_at' => date('Y-m-d H:i:s'), 'betlist_id' => $exist->id]);

            }

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

    public function betLogSaveBup($game_id){

        return false;

        $this->entityClass = Betlists::class;
        $repository = $this->getRepository();

        $arrList = [];
        $arrAdd = [];
        $arrUpdate = [];
        $game_type = "SB";

        $bets = BetlistsTmpBup::where('game_id', $game_id)
            ->where('id', '>=', 915849)
            ->orderBy('tmp_id', 'ASC')
            ->limit(500)
            ->get();

//        return $bets;

        foreach ($bets as $items) {

            $id = $items->id;
            $bet_id = $items->bet_id;
            $item = json_decode($items->data, true);

            $user = str_replace("sbot", "", $item['PlayerName']);
            $username = Username::getUsernameByUser($user);
//            print_r($username);
//            continue;

            $member_id = $username['member_id'];
            $agent_id = $username['agent_id'];
            $username_id = $username['username_id'];
            $board_game_id = $username['game_id'];

            // get member comm
            $comm = MembersCommissions::getMemberCommissions($member_id);

            // Check Game Type
            $gameType = GamesTypes::getTypeByCode($game_type, $board_game_id);
            $game_type_id = $gameType['id'];
//            print_r($gameType);
//            continue;

            // Check taking
            $super_taking = $gameType['taking'] / 100;
            $company_taking = 1 - $super_taking;
            $agent_taking = 0;

            // check commission
            $commission = 0;
            $commission_amount = 0;
            $result_amount = $item['WinLoseAmount'];
            $validAmount = ($result_amount >= 0) ? $result_amount : $result_amount * -1;
            $result_commission_amount = ($validAmount < $item['Stake']) ? $validAmount : $item['Stake'];

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

            $hash = md5($board_game_id.$item['TransId']);

            $arrItem = [
                'member_id' => $member_id,
                'agent_id' => $agent_id,
                'username' => $user,
                'username_id' => $username_id,
                'board_game_id' => $board_game_id,
                'game_type_id' => $game_type_id,
                'bet_id' => $item['TransId'],
                'hash' => $hash,
                'bet_time' => $item['TransactionTime'],
                'payout_time' => $item['settlement_time'],
                'work_time' => $item['TransactionTime'],
                'match_time' => $item['MatchDatetime'],
                'game_id' => $item['MatchId'],
                'host_id' => $item['ParlayRefNo'],
                'host_name' => null,
                'game_type' => $game_type,
                'set' => null,
                'round' => null,
                'bet_type' => $item['parlay_type'],
                'bet_amount' => (string)$item['Stake'],
                'turnover' => (string)$item['Stake'],
                'rolling' => (string)$validAmount,
                'result_amount' => (string)$result_winloss,
                'result_commission_amount' => (string)$result_commission_amount,
                'balance' => (string)$item['AfterAmount'],
                'state' => $item['TicketStatus'],
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

                $repository->setupModel($this->entityClass);
                $entity = $repository->find($exist->id);
                $repository->updateEntity($arrItem, $entity);

                $arrUpdate[] = $entity;

                $arrResult = [
                    'betlist_id' => $exist->id,
                    'game_result' => $detail
                ];
                $repository->createEntity($arrResult, \App::make(BetlistsResults::class));

            }

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


}
