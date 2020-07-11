<?php

namespace App\Http\Controllers\Games\Pussy;

use Illuminate\Http\Request;
use Modules\Core\Games\Entities\GamesTypes;
use Modules\Core\Username\Entities\Username;
use Modules\Member\Members\Entities\MembersCommissions;
use Modules\Report\Betlists\Entities\Betlists;
use Modules\Report\Betlists\Entities\BetlistsResults;
use Modules\Report\Betlists\Entities\BetlistsTmp;

class BetsController extends PussyController
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

    public function getBetItems($debug = false)
    {

        $this->apiUrl = $this->apiUrl2;

//        $setParam = [
//            "userName" => $this->agent,
//            "sDate" => "2020-03-29",
//            "eDate" => "2020-03-31",
//            "Type" => "ServerTotalReport"
//        ];
//        $param = $this->setParam($setParam, 'ashx/AgentTotalReport.ashx');

        $setParam = [
            "userName" => "th57334203609",
            "sDate" => "2020-03-29 00:00:00",
            "eDate" => "2020-03-31 23:59:59",
            "pageIndex" => 1
        ];
        $param = $this->setParam($setParam, 'ashx/AllScoreLog.ashx');

        $response = $this->push(false);
        $response = json_decode($response, true);

        if($debug) {
            return compact('param', 'response');
        }

        $response = json_decode($response, true);

        return $response;
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

            return $bets = $this->getBetItems(true);

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
//            print_r($username);
//            continue;

            $member_id = $username['member_id'];
            $agent_id = $username['agent_id'];
            $username_id = $username['username_id'];
            $board_game_id = $username['game_id'];

            // Check Exist
            $exist = Betlists::where('board_game_id', $board_game_id)
                ->where('bet_id', $bet_id)
                ->first();

            if(empty($exist)) {

                // get member comm
                $comm = MembersCommissions::getMemberCommissions($member_id);

                // Check Game Type
                $game_type = $item['game'];
                $gameType = GamesTypes::getTypeByCode($game_type, $username['game_id']);
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

                $arrItem = [
                    'member_id' => $member_id,
                    'agent_id' => $agent_id,
                    'username' => $item['username'],
                    'username_id' => $username_id,
                    'board_game_id' => $board_game_id,
                    'game_type_id' => $game_type_id,
                    'bet_id' => $bet_id,
                    'bet_time' => $item['bettime'],
                    'payout_time' => $item['caltime'],
                    'work_time' => $item['caltime'],
                    'match_time' => $item['caltime'],
                    'game_id' => $item['ref'],
                    'host_id' => null,
                    'host_name' => null,
                    'game_type' => $game_type,
                    'set' => $item['getCom'],
                    'round' => null,
                    'bet_type' => $item['game_type'],
                    'bet_amount' => $item['turnover'],
                    'rolling' => $validAmount,
                    'detail' => $detail,
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

                $arrUpdate[] = $item;

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
