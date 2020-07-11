<?php

namespace App\Http\Controllers\Games\Lotto;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Core\Agents\Entities\Agents;
use Modules\Core\Agents\Entities\AgentsShare;
use Modules\Core\Boards\Entities\Boards;
use Modules\Core\Games\Entities\GamesTypes;
use Modules\Core\Username\Entities\Username;
use Modules\Member\Members\Entities\MembersCommissions;
use Modules\Report\Betlists\Entities\Betlists;
use Modules\Report\Betlists\Entities\BetlistsResults;
use Modules\Report\Betlists\Entities\BetlistsTmp;

class BetsController extends CshController
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

        $this->apiUrl = "http://betflikbetlog.psg-api.com/api/";
        $setParam = [
            'start' => $this->level,
            'end' => $this->id,
        ];

        $param = $this->setParam($setParam, 'betlog');
        $response = $this->push();

        if($debug) {
            return compact('param', 'response');
        }

        $response = json_decode($response, true);

        return $response;
    }

    public function getBetItemsFix($key = 0, $debug = false)
    {

        $this->apiUrl = "http://betflikbetlog.psg-api.com/fixslot/";
        $setParam = [];
        $param = $this->setParam($setParam, 'getFixJoker');
        $response = $this->push();

        if($debug) {
            return compact('param', 'response');
        }

        $response = json_decode($response, true);

        return $response;
    }

    public function getBetItemsByTime($user, $debug = false)
    {

        $this->apiUrl = "http://betflikbetlog.psg-api.com/api/";

        $setParam = [
            'username' => $user,
            'start' => '2020-04-10 21:00:00',
            'end' => '2020-04-10 23:59:59',
        ];

        $param = $this->setParam($setParam, 'getBetlogByTime');
        $response = $this->push();

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

            $arrAdd = [];
            $arrUpdate = [];
            $board_game_id = $board['game_id'];

            if(empty($board['lastkey'])){
                $lastKey = 0;
            }else{
                $lastKey = $board['lastkey'];
            }

            $bets = $this->getBetItemsByTime($key['agent']);

            $arrList[$key['agent']] = [
                'count' => count($bets['data'])
            ];

//            print_r($arrList);
//            continue;

            if($bets['status'] != "success"){
                continue;
            }

            $items = $bets['data'];

            if(count($items) == 0){
                continue;
            }

            foreach ($items as $item) {

                $user = $item['username'];

                $hash = $board_game_id.$item['id'];
                $md5 = md5($hash);

                $exist = BetlistsTmp::where('hash', $md5)->first();

                if(empty($exist)) {

                    $arrTmp = [
                        'game_id' => $board_game_id,
                        'hash' => $md5,
                        'bet_id' => $item['id'],
                        'last_key' => $item['id'],
                        'agent_ref' => $key['agent'],
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

                $lastKey = $item['id'];

            }

            /**
             * Update Last Key
             */
            Boards::where(['ref' => $board['ref']])->update(['lastkey' => $lastKey]);

            $arrList[$key['agent']] = [
                'add' => $arrAdd,
                'update' => $arrUpdate
            ];

            $arrCount[$key['agent']] = [
                'add' => count($arrAdd),
                'update' => count($arrUpdate),
                'lastKey' => $lastKey
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

            $arrAdd = [];
            $arrUpdate = [];
            $board_game_id = $board['game_id'];

            return $bets = $this->getBetItems();

            if($bets['status'] != "success"){
                continue;
            }

            $items = $bets['data'];

            if(count($items) == 0){
                $items = [];
            }

            foreach ($items as $item) {

                $user = $item['username'];

                $hash = $board_game_id.$item['id'];
                $md5 = md5($hash);

                $exist = BetlistsTmp::where('hash', $md5)->first();

                if(empty($exist)) {

                    $arrTmp = [
                        'game_id' => $board_game_id,
                        'hash' => $md5,
                        'bet_id' => $item['id'],
                        'last_key' => $item['id'],
                        'agent_ref' => $key['agent'],
                        'data' => json_encode($item, JSON_UNESCAPED_UNICODE),
                    ];
                    $entity = $repository->createEntity($arrTmp, \App::make(BetlistsTmp::class));

                    $arrAdd[] = $entity;

                    $lastKey = $item['id'];

                }
                /**
                 * Update
                 */
                else{

                    $arrUpdate[] = $item;

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
            ];

        } // End foreach boards

        return compact('arrCount', 'arrList');

    }

    public function betLogTmpFix($boards){

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

            $bets = $this->getBetItemsFix();

            if($bets['status'] != "success"){
                continue;
            }

            $items = $bets['data'];

            if(count($items) == 0){
                $items = [];
            }

            foreach ($items as $item) {

                $user = $item['username'];

                $hash = $board_game_id.$item['id'];
                $md5 = md5($hash);

                $exist = BetlistsTmp::where('hash', $md5)->first();

                if(empty($exist)) {

                    $arrTmp = [
                        'game_id' => $board_game_id,
                        'hash' => $md5,
                        'bet_id' => $item['id'],
                        'last_key' => $item['id'],
                        'agent_ref' => $key['agent'],
                        'data' => json_encode($item, JSON_UNESCAPED_UNICODE),
                    ];
                    $entity = $repository->createEntity($arrTmp, \App::make(BetlistsTmp::class));

                    $arrAdd[] = $entity;

                    $lastKey = $item['id'];

                }
                /**
                 * Update
                 */
                else{

                    $arrUpdate[] = $item;

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
            ->whereNull('status')
            ->orderBy('id', 'ASC')
            ->limit(500)
            ->get();

        foreach ($bets as $items) {

            $id = $items->id;
            $bet_id = $items->bet_id;
            $item = json_decode($items->data, true);

//            print_r($item);
//            continue;

            $user = $item['username'];
            $username = Username::getUsernameByUser($user);

            if(empty($username)){
                BetlistsTmp::where(['id' => $id])->update(['status' => 3, 'request_at' => date('Y-m-d H:i:s')]);
                continue;
            }

//            continue;

            $member_id = (!empty($username['member_id'])) ? $username['member_id'] : 0;
            $agent_id = (!empty($username['agent_id'])) ? $username['agent_id'] : $username['b_agent_id'];
            $username_id = $username['username_id'];
            $board_game_id = $username['game_id'];

            // get member comm
            $comm = MembersCommissions::getMemberCommissions($member_id);

            // Check Game Type
            $game_type = $item['game'];
            $gameType = GamesTypes::getTypeByCode($game_type, $username['game_id']);
            $game_type_id = $gameType['id'];
//                print_r($gameType);
//                continue;

            // Check taking
            $all_taking = $gameType['taking'] / 100;

            $agent_share = AgentsShare::getAgentShare($agent_id);
            $agent_taking = 0;
            if(isset($agent_share[$board_game_id][$game_type_id])){
                $agent_taking = $agent_share[$board_game_id][$game_type_id] / 100;
            }
            $super_taking = $all_taking - $agent_taking;
            $company_taking = 1 - $all_taking;

//                print_r(compact('all_taking', 'agent_taking', 'super_taking', 'company_taking'));
//                continue;

            // check commission
            $commission = 0;
            $commission_amount = 0;
            $validAmount = $item['valid_amount'];
//                if (isset($comm[$board_game_id][$game_type_id])) {
//                    $commission = $comm[$board_game_id][$game_type_id] / 100; // b/c show on system 0.00 - 1.00
//                    $commission_amount = $validAmount * $commission;
//                }

            $commission_amount = $item['commission'];
            $result_amount = $item['winloss'];

            $result_winloss = $result_amount;
            $result_rolling = $validAmount; // use for calculate commission
            // check taking
            $taking_winloss = $result_amount * -1;
            $taking_commission = ($commission_amount != 0) ? $commission_amount * -1 : 0;

            // agent
            $agent_winloss = $taking_winloss * $agent_taking;
            $agent_amount = $result_rolling * $agent_taking;
            $agent_commission = $taking_commission * $agent_taking;

            // super taking amount and comm
            $super_winloss = $taking_winloss * $super_taking;
            $super_amount = $result_rolling * $super_taking;
            $super_commission = $taking_commission * ($super_taking+$company_taking); // super response 100%

            // company
            $company_winloss = $taking_winloss * $company_taking;
            $company_amount = $result_rolling * $company_taking;
            $company_commission = 0;

            $detail = json_encode($item, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

            $arrItem = [
                'member_id' => $member_id,
                'agent_id' => $agent_id,
                'username' => $item['username'],
                'username_id' => $username_id,
                'board_game_id' => $board_game_id,
                'game_type_id' => $game_type_id,
                'bet_id' => $bet_id,
                'hash' => $items['hash'],
                'bet_time' => $item['bettime'],
                'payout_time' => $item['bettime'],
                'work_time' => $item['bettime'],
                'match_time' => $item['bettime'],
                'game_id' => $item['ref'],
                'host_id' => null,
                'host_name' => null,
                'game_type' => $game_type,
                'set' => $item['getCom'],
                'round' => null,
                'bet_type' => $item['game_type'],
                'bet_amount' => $item['turnover'],
                'turnover' => $item['turnover'],
                'rolling' => $validAmount,
                'result_amount' => $result_winloss,
                'balance' => null,
                'state' => null,
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
            $exist = Betlists::where('hash', $items['hash'])->first();

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


}
