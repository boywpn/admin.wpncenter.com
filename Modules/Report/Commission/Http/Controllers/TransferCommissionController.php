<?php

namespace Modules\Report\Commission\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Api\Http\Controllers\Game\GameApiController;
use Modules\Api\Http\Controllers\Game\TransferApiController;
use Modules\Core\Games\Entities\Games;
use Modules\Platform\Core\Repositories\GenericRepository;
use Modules\Report\Betlists\Entities\Betlists;
use Modules\Report\Betlists\Entities\BetlistsResults;
use Modules\Report\Commission\Entities\Commission;

class TransferCommissionController extends GameApiController
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    private $arrId = [];

    public function keep_transfer($game)
    {

        $game = Games::where('code', $game)->first();
        if(!$game){
            return [];
        }

        $game_id = $game->id;
        $minute = date('i');

//        $from_date = genDateFilter('0 day');
//        $from = $from_date.' 12:00:00';
//        $to_date = genDateFilter('1 day');
//        $to = $to_date.' 11:59:59';

        if($minute > 0 && $minute <=30) {
            $from = date('Y-m-d H').":58:00";
            $to = date('Y-m-d H').":29:59";
        }elseif($minute > 30){
            $from = date('Y-m-d H').":28:00";
            $to = date('Y-m-d H').":59:59";

            $from = changeDate('1 hour', $from, true);
        }

        /**
         * GMT+8
        */
        // $from = changeDate('1 hour', $from, true);
        $to = changeDate('1 hour', $to, true);
        $to_date = date('Y-m-d');

//        echo $minute." ".$from." ".$to;
//        exit;

        $lists = $this->getList($game_id, $from, $to);

        $repository = \App::make(GenericRepository::class);
        $repository->setupModel(Commission::class);

        $arrLoop = [];
        $arrData = [];
        $entity = [];
        foreach ($lists as $username_id => $list) {

            $ck_exist = Commission::where('username_id', $username_id)
                ->where('status', 1)
                ->where('pay_time', $to)
                ->first();

            if(!$ck_exist){

                $arrData = [
                    'member_id' => $list['member_id'],
                    'username_id' => $list['username_id'],
                    'game_id' => $game_id,
                    'date' => $to_date,
                    'pay_time' => $to,
                    'amount' => $list['amount'],
                    'status' => 1,
                ];

                $entity = $repository->createEntity($arrData, \App::make(Commission::class));

            }else{

                $arrData = [
                    'amount' => $list['amount'] + $ck_exist->amount
                ];
                $entity = $repository->updateEntity($arrData, $ck_exist);

            }

            // Update List
            foreach ($list['id'] as $id) {
                $arrUpdate = [
                    'trans_commission_status' => 2,
                    'trans_commission_id' => $entity->id,
                    'trans_commission_at' => date('Y-m-d H:i:s')
                ];
                Betlists::where('id', $id)->update($arrUpdate);
            }

            $arrLoop[$username_id] = [
                'ck_exist' => $ck_exist,
                'data_insert' => $arrData,
                'entity' => $entity,
            ];

        }

        $data['from'] = $from;
        $data['to'] = $to;
        $data['loop'] = $arrLoop;

        $data['record'] = count($lists);
        $data['data'] = $lists;

        return $data;

    }

    public function keepTransferByUser($game, $user)
    {

        $game = Games::where('code', $game)->first();
        if(!$game){
            return [];
        }

        $game_id = $game->id;
        $minute = date('i');

        if($minute > 0 && $minute <=30) {
            $from = date('Y-m-d H').":58:00";
            $to = date('Y-m-d H').":29:59";
        }elseif($minute > 30){
            $from = date('Y-m-d H').":28:00";
            $to = date('Y-m-d H').":59:59";

            $from = changeDate('1 hour', $from, true);
        }

        /**
         * GMT+8
         */
        // $from = changeDate('1 hour', $from, true);
        $to = changeDate('1 hour', $to, true);
        $to_date = date('Y-m-d');

//        echo $minute." ".$from." ".$to;

        // $lists = $this->getListByUser($game_id, $user, '2020-02-14 03:00:00', '2020-02-14 06:00:00');
        $lists = $this->getList($game_id, '2020-02-14 03:00:00', '2020-02-14 06:00:00');

//        print_r($lists);
//        exit;

        $repository = \App::make(GenericRepository::class);
        $repository->setupModel(Commission::class);

        $arrLoop = [];
        $arrData = [];
        $entity = [];
        foreach ($lists as $username_id => $list) {

            $ck_exist = Commission::where('username_id', $username_id)
                ->where('status', 1)
                ->where('pay_time', $to)
                ->first();

            if(!$ck_exist){

                $arrData = [
                    'member_id' => $list['member_id'],
                    'username_id' => $list['username_id'],
                    'game_id' => $game_id,
                    'date' => $to_date,
                    'pay_time' => $to,
                    'amount' => $list['amount'],
                    'status' => 1,
                ];

                $entity = $repository->createEntity($arrData, \App::make(Commission::class));

            }else{

                $arrData = [
                    'amount' => $list['amount'] + $ck_exist->amount
                ];
                $entity = $repository->updateEntity($arrData, $ck_exist);

            }

            // Update List
            foreach ($list['id'] as $id) {
                $arrUpdate = [
                    'trans_commission_status' => 2,
                    'trans_commission_id' => $entity->id,
                    'trans_commission_at' => date('Y-m-d H:i:s')
                ];
                Betlists::where('id', $id)->update($arrUpdate);
            }

            $arrLoop[$username_id] = [
                'ck_exist' => $ck_exist,
                'data_insert' => $arrData,
                'entity' => $entity,
            ];

        }

        $data['from'] = $from;
        $data['to'] = $to;
        $data['loop'] = $arrLoop;

        $data['record'] = count($lists);
        $data['data'] = $lists;

        return $data;

    }

    public function getList($game, $from, $to){

        $query = DB::table('report_betlists AS rb')
            ->where('rb.board_game_id', $game)
            ->where('rb.trans_commission_status', 1)
            ->select(
                'rb.id',
                'rb.rolling',
                'rb.commission_amount',
                'ca.name',
                'ca.ref',
                'cb.member_prefix',
                'mm.id AS member_id',
                'mm.name AS member_name',
                'cu.id AS username_id',
                'cu.username',
                'cg.name AS game_name'
            )
            ->leftJoin('core_agents AS ca', 'ca.id', '=', 'rb.agent_id')
            ->leftJoin('member_members AS mm', 'mm.id', '=', 'rb.member_id')
            ->leftJoin('core_username AS cu', 'cu.id', '=', 'rb.username_id')
            ->leftJoin('core_games AS cg', 'cg.id', '=', 'rb.board_game_id')
            ->leftJoin('core_games_types AS cgt', 'cgt.id', '=', 'rb.game_type_id')
            ->leftJoin('core_boards AS cb', 'cb.id', '=', 'cu.board_id')
            ->whereBetween('rb.payout_time', [$from, $to])
            // ->groupBy('rb.member_id')

            ->get();

        if(count($query) == 0){
            return [];
        }

        $data = [];
        $arrId = [];
        foreach ($query as $list){
            $data[$list->username_id]['id'][] = $list->id;
            $data[$list->username_id]['member_id'] = $list->member_id;
            $data[$list->username_id]['member_name'] = $list->member_name;
            $data[$list->username_id]['username_id'] = $list->username_id;
            $data[$list->username_id]['username'] = $list->username;
            $data[$list->username_id]['amount'] = ((isset($data[$list->username_id]['amount'])) ? $data[$list->username_id]['amount'] : 0) + $list->commission_amount;
        }

        return $data;

    }

    public function getListByUser($game, $user, $from, $to){

        $query = DB::table('report_betlists AS rb')
            ->where('rb.board_game_id', $game)
            ->where('rb.trans_commission_status', 1)
            ->where('cu.username', $user)
            ->select(
                'rb.id',
                'rb.rolling',
                'rb.commission_amount',
                'ca.name',
                'ca.ref',
                'cb.member_prefix',
                'mm.id AS member_id',
                'mm.name AS member_name',
                'cu.id AS username_id',
                'cu.username',
                'cg.name AS game_name'
            )
            ->leftJoin('core_agents AS ca', 'ca.id', '=', 'rb.agent_id')
            ->leftJoin('member_members AS mm', 'mm.id', '=', 'rb.member_id')
            ->leftJoin('core_username AS cu', 'cu.id', '=', 'rb.username_id')
            ->leftJoin('core_games AS cg', 'cg.id', '=', 'rb.board_game_id')
            ->leftJoin('core_games_types AS cgt', 'cgt.id', '=', 'rb.game_type_id')
            ->leftJoin('core_boards AS cb', 'cb.id', '=', 'cu.board_id')
            ->whereBetween('rb.payout_time', [$from, $to])
            // ->groupBy('rb.member_id')

            ->get();

        if(count($query) == 0){
            return [];
        }

        $data = [];
        $arrId = [];
        foreach ($query as $list){
            $data[$list->username_id]['id'][] = $list->id;
            $data[$list->username_id]['member_id'] = $list->member_id;
            $data[$list->username_id]['member_name'] = $list->member_name;
            $data[$list->username_id]['username_id'] = $list->username_id;
            $data[$list->username_id]['username'] = $list->username;
            $data[$list->username_id]['amount'] = ((isset($data[$list->username_id]['amount'])) ? $data[$list->username_id]['amount'] : 0) + $list->commission_amount;
        }

        return $data;

    }

    public function transfer($game){

        $game = Games::where('code', $game)->first();
        if(!$game){
            return [];
        }

        $game_id = $game->id;
        $date = date('Y-m-d');
        $minute = date('i');

        if($minute > 0 && $minute <=30) {
            $from = date('Y-m-d H').":30:00";
            $to = date('Y-m-d H').":59:59";
        }elseif($minute > 30){
            $from = date('Y-m-d H').":00:00";
            $to = date('Y-m-d H').":29:59";

            /**
             * GMT+8
             */
            $from = changeDate('1 hour', $from, true);
            $to = changeDate('1 hour', $to, true);

        }

//        echo $to;
//        exit;

        $row = Commission::where('status', 1)
            ->where('pay_time', $to)
            ->where('game_id', $game_id)
            ->with(['commUsername' => function($query){
                $query->select('id', 'username');
            }])
            ->get();

        if(count($row) == 0){
            return [];
        }

        $repository = \App::make(GenericRepository::class);
        $repository->setupModel(Commission::class);

        $data = $row->toArray();

        $trans = new TransferApiController();
        $res = [];
        foreach ($data as $list){

            $entity = $repository->findWithoutFail($list['id']);

            $arrTrans = [
                'comm_id' => $list['id'],
                'action' => 'transfer',
                'orderid' => 'comm_auto',
                'custid' => $list['comm_username']['username'],
                'type' => 'transfer',
                'amount' => $list['amount'],
                'staffid' => 1,
                'from' => 'api_auto_commission',
                'stateid' => '333333',
                'local_ip' => get_client_ip(),
                'auto' => true,
            ];

            $arrRequest = [
                'request_time' => date('Y-m-d H:i:s'),
                'request_data' => json_encode($arrTrans, JSON_UNESCAPED_UNICODE),
            ];

            $repository->updateEntity($arrRequest, $entity);

            $response = $trans->transfer($arrTrans);

            $arrResponse = [
                'response_time' => date('Y-m-d H:i:s'),
                'response_data' => json_encode($response, JSON_UNESCAPED_UNICODE),
                'ref_code' => ($response['responseStatus']['code'] == 200) ? $response['responseDetails']['order_api_id'] : null,
                'status' => ($response['responseStatus']['code'] == 200) ? 2 : 3,
            ];

            $entity = $repository->updateEntity($arrResponse, $entity);

            $res[] = $entity;
        }

        return $res;

    }

}
