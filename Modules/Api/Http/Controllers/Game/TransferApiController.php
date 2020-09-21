<?php

namespace Modules\Api\Http\Controllers\Game;

use App\Http\Controllers\Games\Dg\MemberController AS DG;
use App\Http\Controllers\Games\Og\MemberController AS OG;
use App\Http\Controllers\Games\Sa\MemberController AS SA;
use App\Http\Controllers\Games\Aec\MemberController AS AEC;
use App\Http\Controllers\Games\Sbo\MemberController AS SBO;
use App\Http\Controllers\Games\Sexy\MemberController AS SEXY;
use App\Http\Controllers\Games\Ibc\MemberController AS IBC;
use App\Http\Controllers\Games\Lotto\MemberController AS LOTTO;
use App\Http\Controllers\Games\Tiger\MemberController AS TIGER;
use App\Http\Controllers\Games\Csh\MemberController AS CSH;
use App\Http\Controllers\Games\Pussy\MemberController AS PUSSY;
use App\Http\Controllers\Games\Tfg\MemberController AS TFG;
use App\Http\Controllers\Games\Trnf\MemberController AS TRNF;
use App\Models\Old\Bonus;
use App\Models\Old\BonusLog;
use App\Models\TransfersLog;
use App\Models\Trnf\BankStatement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Modules\Core\Games\Entities\Games;
use Modules\Core\Username\Entities\Username;
use Modules\Job\Jobs\Entities\Jobs;
use Modules\Member\Members\Entities\Members;
use Modules\Platform\Core\Repositories\GenericRepository;

/**
 *
 * Class ContactsApiController
 *
 * @package Modules\Api\Http\Controllers
 */
class TransferApiController extends GameApiController
{

    public function setTransfer(Request $request){

        $data = $request->input();

        return $this->transfer($data);

    }

    public function approve($request){

        $data = $request;

        $custom = json_decode($data['custom'], true);

        if(!isset($data['tran_id']) || $data['tran_id'] == ""){
            $this->resJson['responseStatus']['code'] = 201;
            $this->resJson['responseStatus']['message'] = "ERROR";
            $this->resJson['responseStatus']['messageDetails'] = 'กรุณาระบุหมายเลข Transfer';
            return $this->returnJson();
        }
        if(!isset($data['stateid']) || $data['stateid'] == ""){
            $this->resJson['responseStatus']['code'] = 201;
            $this->resJson['responseStatus']['message'] = "ERROR";
            $this->resJson['responseStatus']['messageDetails'] = 'กรุณาระบุหมายเลข รายการธนาคาร';
            return $this->returnJson();
        }
        if(!isset($data['approve_key']) || $data['approve_key'] == ""){
            $this->resJson['responseStatus']['code'] = 201;
            $this->resJson['responseStatus']['message'] = "ERROR";
            $this->resJson['responseStatus']['messageDetails'] = 'กรุณาระบุหมายเลข Approve Key';
            return $this->returnJson();
        }

        if(!in_array($data['stateid'], array('112233','168168','335588'))) {
            $checkStatement = TransfersLog::where('statement_id', $data['stateid'])->count();
            if ($checkStatement > 0) {
                $this->resJson['responseStatus']['code'] = 201;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = 'รายการธนาคาร ถูกใช้แล้ว ไม่สามารถใช้ซ้ำได้';
                return $this->returnJson();
            }
        }

        $repository = \App::make(GenericRepository::class);
        $repository->setupModel(Jobs::class);
        $entity = $repository->find($data['tran_id']);
        if(empty($entity)){
            $this->resJson['responseStatus']['code'] = 201;
            $this->resJson['responseStatus']['message'] = "ERROR";
            $this->resJson['responseStatus']['messageDetails'] = 'ไม่พบข้อมูลรายการที่ต้องการยืนยัน';
            return $this->returnJson();
        }

        // $app = getDescArr("tran_id","sh_transfer_log","tran_id='".$tran_id."' AND tran_approve_key='".$approve_key."' AND tran_stat='1'");
        $transfer = TransfersLog::where('job_id', $data['tran_id'])
            ->where('order_code', $data['orderid'])
            ->where('approve_key', $data['approve_key'])
            ->first();

        if(!$transfer){
            $this->resJson['responseStatus']['code'] = 201;
            $this->resJson['responseStatus']['message'] = "ERROR";
            $this->resJson['responseStatus']['messageDetails'] = 'รายการที่ต้องการ Approve ไม่ถูกต้อง';
            return $this->returnJson();
        }

        if($transfer->status == 2){
            $this->resJson['responseStatus']['code'] = 201;
            $this->resJson['responseStatus']['message'] = "ERROR";
            $this->resJson['responseStatus']['messageDetails'] = 'รายการที่ต้องการ Approve ถูกยืนยันก่อนแล้ว';
            return $this->returnJson();
        }

        // update job
        $arrUpdate = array(
            'approved_at' => date('Y-m-d H:i:s'),
            'locked_by_name' => $custom['admin_name'],
            'statement_id' => $data['stateid'],
        );
        $repository->updateEntity($arrUpdate, $entity);

        TransfersLog::where('id', $transfer->id)
            ->update(['status' => 2, 'statement_id' => $data['stateid'], 'updated_at' => date('Y-m-d H:i:s')]);

        BankStatement::where('state_id', $data['stateid'])
            ->update(['order_id' => $data['orderid'], 'state_status' => '1', 'updated_at' => date('Y-m-d H:i:s')]);

        $this->resJson['responseStatus']['code'] = 200;
        $this->resJson['responseStatus']['message'] = "SUCCESS";
        $this->resJson['responseStatus']['messageDetails'] = 'ยืนยันรายการเรียบร้อย';

        $this->resJson['responseDetails']['staffid'] = (isset($custom['admin_id'])) ? $custom['admin_id'] : null;
        $this->resJson['responseDetails']['staffname'] = (isset($custom['admin_name'])) ? $custom['admin_name'] : null;
        $this->resJson['responseCustom'] = (!empty($custom)) ? $custom : null;

        return $this->returnJson();

    }

    public function getUsername($id){

        $user = Username::where('username', $id)
            ->with(['usernameBoard' => function($query){
                $query->select('*');
            }, 'usernameBoard.boardsGame' => function($query){
                $query->select('*');
            }, 'usernameBoard.boardsPartner' => function($query){
                $query->select('*');
            }, 'usernameBoard.usersBoard' => function($query){
                $query->select('*')->inRandomOrder()->first();
            }])
            ->first();

        return $user;
    }

    public function transfer($data)
    {

        $order = (isset($data['orderid'])) ? $data['orderid'] : null;
        $from = (isset($data['from'])) ? $data['from'] : null;
        $log_agent_id = (isset($data['log_agent_id'])) ? $data['log_agent_id'] : null;
        // $type_id = ($request->input('amount') > 0) ? 1 : 2;

        $this->arrLog['log_agent_id'] = $log_agent_id;

        $custom = [];
        if(isset($data['custom'])) {
            $custom = json_decode($data['custom'], true);
        }

        // for type approve almost from withdrawal
        if($data['type'] == "approve"){
            return $this->approve($data);
        }

        // if type is transfer have to put order id
        if($data['type'] == "transfer"){
            if(!isset($data['orderid']) || $data['orderid'] == ""){
                $this->resJson['responseStatus']['code'] = 201;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = 'กรุณาใส่หมายเลขรายการให้ถูกต้อง';
                return $this->returnJson();
            }

            // check order_code exist
            $transfer = TransfersLog::where('order_code', $data['orderid'])->count();
            if($transfer > 0 && !in_array($order, GameApiController::ACCEPT_ORDER_CODE)){
                $this->resJson['responseStatus']['code'] = 201;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = $data['orderid'].' ไม่สามารถทำซ้ำได้อีก';
                return $this->returnJson();
            }
        }

        if(!empty($order) && !in_array($order, GameApiController::ACCEPT_ORDER_CODE)) {

            $check = $this->checkOrderRequest($data);
            if(!$check){
                return $this->returnJson();
            }

            $job = $this->getJob($order);
            if (!$job) {
                return $this->returnJson();
            }

            $repository = \App::make(GenericRepository::class);
            $repository->setupModel(Jobs::class);

            // $entity = $repository->findWithoutFail($job['id']);
            $entity = $repository->find($job['id']);

            $game = $job['game'];
            $game_id = $job['game_id'];
            $key = $job['key'];
            $username = $job['username'];
            $password = $job['password'];
            $username_id = $job['username_id'];
            $amount = ($job['transfer_type'] == 1 || $job['transfer_type'] == 4) ? $job['total_amount'] : $job['total_amount']*-1;
            $aguser = $job['aguser'];
            $job_id = $job['id'];
            $order_code = $job['order_code'];

        }
        /**
         * From backup request
         */
        else{

            $check = $this->checkRequest($data);
            if(!$check){
                return $this->returnJson();
            }

            $user = $this->getUsername($data['custid']);

            if (!$user) {
                $this->resJson['responseStatus']['code'] = 201;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = 'Username ไม่มีอยู่ในระบบ';
                return $this->returnJson();
            }

            $user = $user->toArray();

//            if(!isset($user['username_board']['boards_game']['code'])){
//                Artisan::call('cache:clear');
//
//                $user = $this->getUsername($data['custid']);
//            }

            $game = strtolower($user['username_board']['boards_game']['code']);
            $game_id = $user['username_board']['boards_game']['id'];
            $key = json_decode($user['username_board']['api_code'], true);
            $username = $user['username'];
            $password = Crypt::decryptString($user['password']);
            $username_id = $user['id'];
            $amount = $data['amount'];
            $aguser = $user['username_board']['member_prefix'];

            $job_id = (isset($data['job_id'])) ? $data['job_id'] : null;
            $order_code = (isset($data['orderid'])) ? $data['orderid'] : null;

        }

        /**
         * Check Maintenance
        */
        $ck_game = Games::where('id', $game_id)->where('is_maintenance', 1)->count();
        if($ck_game > 0){
            $this->resJson['responseStatus']['code'] = 203;
            $this->resJson['responseStatus']['message'] = "ERROR";
            $this->resJson['responseStatus']['messageDetails'] = "ขณะนี้ระบบกำลังปิดปรับปรุง...";
            return $this->returnJson();
        }

        /**
         * Check have promotion exist
        */
//        if($data['type'] == "transfer") {
//            if (in_array($order, self::PROMO_CHECK)) {
//                // check amount
//                $promo = self::PROMO_CHECK_VAL[$order];
//                if ($amount > $promo['amount']) {
//                    $this->resJson['responseStatus']['code'] = 201;
//                    $this->resJson['responseStatus']['message'] = "ERROR";
//                    $this->resJson['responseStatus']['messageDetails'] = 'ไม่สามารถทำรายการโปรโมชั่นจำนวนเงินนี้ได้ ' . $amount . '>' . $promo['amount'];
//                    return $this->returnJson();
//                }
//            }
//
//            if (!in_array($data['from'], self::PROMO_ALLOW_CHECK_FROM)) {
//                $u = Username::findOrFail($username_id);
//                $m = Members::findOrFail($u->member_id);
//                if ($u->have_promo == 1) {
//                    $this->resJson['responseStatus']['code'] = 201;
//                    $this->resJson['responseStatus']['message'] = "ERROR";
//                    $this->resJson['responseStatus']['messageDetails'] = 'Username นี้มีโปรโมชั่นค้างอยู่ ' . $u->promo_code . ' สามารถยกเลิกโปรได้ในหน้าจัดการกิจกรรม.';
//                    return $this->returnJson();
//                }
//                if ($m->have_promo == 1) {
//                    $this->resJson['responseStatus']['code'] = 201;
//                    $this->resJson['responseStatus']['message'] = "ERROR";
//                    $this->resJson['responseStatus']['messageDetails'] = 'Member นี้มีโปรโมชั่นค้างอยู่ ' . $m->promo_code . ' สามารถยกเลิกโปรได้ในหน้าจัดการกิจกรรม.';
//                    return $this->returnJson();
//                }
//            }
//        }
        /**
        */

        /**
         * Check Bonus 2019
        */

//        $date = date('Y-m-d H:i:s');
//        // if($username == "sashs0001" && $amount > 0){
//        if($date >= "2019-12-26 08:00:00" && $date <= "2019-12-27 23:59:59" && $amount > 0 && $data['type'] == "transfer" && $order != "comm_auto" && !in_array($order, self::ACCEPT_ORDER_CODE) && !stristr($order, "_dp")){
//
//            $bonus = \App\Models\Old\Username::where('game_user.username', $username)
//                ->join('member' , 'game_user.member_id', '=', 'member.id')
//                ->join('member_bonus' , function($join){
//                    $join->on('member.id', '=', 'member_bonus.member_id')
//                        // ->where('member_bonus.status', 0)
//                        ->whereNull('member_bonus.received_at')
//                        ->whereRaw('member_bonus.domain = member.domain');
//                })
//                ->select(
//                    'game_user.A_I AS u_id',
//                    'game_user.username AS u_username',
//                    'game_user.domain AS u_domain',
//                    'member.A_I AS m_id',
//                    'member.id AS member_id',
//                    'member.username AS m_username',
//                    'member.domain',
//                    'member.created',
//                    'member_bonus.member_ai',
//                    'member_bonus.bonus',
//                    'member_bonus.status',
//                    'member_bonus.id AS mb_id')
//                ->first();
//
//            if($bonus){
//
//                $amount = $bonus->bonus + $amount;
//                $from .= "_bonus";
//
//                $arrUpdate = [
//                    'status' => 1,
//                    'received_at' => $date,
//                    'order_id' => $order,
//                    'total_amount' => $amount,
//                    'statement_id' => $data['stateid'],
//                    'username' => $username,
//                    'username_id' => $bonus->u_id,
//                    'transfer_from' => $from
//                ];
//                Bonus::where('id', $bonus->mb_id)->update($arrUpdate);
//
//                // Save to Bonus Log
//                $arrLog = [
//                    'status' => 1,
//                    'mb_id' => $bonus->mb_id,
//                    'member_id' => $bonus->member_id,
//                    'amount' => $bonus->bonus,
//                    'username' => $username,
//                    'username_id' => $bonus->u_id,
//                    'created_at' => $date,
//                    'updated_at' => $date,
//                    'type' => 1,
//                ];
//                $repository = \App::make(GenericRepository::class);
//                $repository->createEntity($arrLog, \App::make(BonusLog::class));
//            }
//
//        }
//
//        if($date >= "2019-12-26 08:00:00" && $date <= "2019-12-27 23:59:59" && $amount > 0 && $order == '168168_bn'){
//
//            $bonus = \App\Models\Old\Username::where('game_user.username', $username)
//                ->join('member' , 'game_user.member_id', '=', 'member.id')
//                ->join('member_bonus' , function($join){
//                    $join->on('member.id', '=', 'member_bonus.member_id')
//                        // ->where('member_bonus.status', 0)
//                        ->whereNull('member_bonus.received_at')
//                        ->whereRaw('member_bonus.domain = member.domain');
//                })
//                ->select(
//                    'game_user.A_I AS u_id',
//                    'game_user.username AS u_username',
//                    'game_user.domain AS u_domain',
//                    'member.A_I AS m_id',
//                    'member.id AS member_id',
//                    'member.username AS m_username',
//                    'member.domain',
//                    'member.created',
//                    'member_bonus.member_ai',
//                    'member_bonus.bonus',
//                    'member_bonus.status',
//                    'member_bonus.id AS mb_id')
//                ->first();
//
//            if($bonus){
//
////                $amount = $bonus->bonus + $amount;
////                $from .= "_bonus";
//
//                $arrUpdate = [
//                    'status' => 1,
//                    'received_at' => $date,
//                    'order_id' => $order,
//                    'total_amount' => $amount,
//                    'statement_id' => $data['stateid'],
//                    'username' => $username,
//                    'username_id' => $bonus->u_id,
//                    'transfer_from' => $from
//                ];
//                Bonus::where('id', $bonus->mb_id)->update($arrUpdate);
//
//                // Save to Bonus Log
//                $arrLog = [
//                    'status' => 1,
//                    'mb_id' => $bonus->mb_id,
//                    'member_id' => $bonus->member_id,
//                    'amount' => $bonus->bonus,
//                    'username' => $username,
//                    'username_id' => $bonus->u_id,
//                    'created_at' => $date,
//                    'updated_at' => $date,
//                    'type' => 1,
//                ];
//                $repository = \App::make(GenericRepository::class);
//                $repository->createEntity($arrLog, \App::make(BonusLog::class));
//            }
//
//        }

        /**
         * End Bonus 2019
        */

        // if detail for check withdraw
        $checkwd = $amount;
        if($amount < 0){
            $checkwd = $amount * -1;
        }

        $afterbalance = 0;
        $agcredit_bf = 0;
        $agcredit_af = 0;
        $res_order_id = "";
        $approve_key = Str::random(50);
        $statuswd = 204; // check for withdraw on frontend

        // Start Transfer
        if($game == "sa"){

            $api = new SA($key);

            $user = $api->getUser($username);
            if($user['ErrorMsgId'] != 0){
                $this->resJson['responseStatus']['code'] = 203;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = "ไม่มี ".$username." ในเว็บคาสิโน กรุณาสร้างจากระบบใหม่";
                return $this->returnJson();
            }
            $balance = (int)$user['Balance'];
            $playing = (int)$user['BettedAmount'];
            $netbalance = $balance - $playing;

            if($checkwd <= $netbalance){
                $statuswd = 200;
            }

            if($data['type'] == "transfer") {

                if($checkwd > $netbalance && $amount < 0){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "จำนวนเครดิตของท่านไม่พอถอน";
                    return $this->returnJson();
                }

                if($amount > 0 && $amount < 100 && !in_array($order, GameApiController::ACCEPT_MIN_AMOUNT)){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "ฝากขั้นต่ำ 100 บาทขึ้นไป";
                    return $this->returnJson();
                }

                if($amount < 0 && $amount > -100){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "ถอนขั้นต่ำ 100 บาทขึ้นไป";
                    return $this->returnJson();
                }

                /**
                 * Create Log Before Transfer
                 */
                $this->arrLog['job_id'] = $job_id;
                $this->arrLog['order_code'] = $order_code;
                $this->arrLog['amount'] = $amount;
                $this->arrLog['game_id'] = $game_id;
                $this->arrLog['game_code'] = $game;
                $this->arrLog['username_id'] = $username_id;
                $this->arrLog['username'] = $username;
                $this->arrLog['agent_login'] = $aguser;
                $this->arrLog['credit_bf'] = (string)$balance;
                $this->arrLog['agcredit_bf'] = (string)$agcredit_bf;
                $this->arrLog['status'] = 1;
                $log = $this->saveLog($data);
                $this->resJson['responseDetails']['requestApprove']['log_id'] = $log->id;

                $setParam = [
                    'method' => ($amount > 0) ? 'CreditBalanceDV' : 'DebitBalanceDV',
                    'Username' => $username,
                    'OrderId' => (($amount > 0) ? 'IN' : 'OUT') . date('YmdHis') . $username,
                ];
                if($amount > 0){
                    $setParam['CreditAmount'] = $amount;
                }else{
                    $setParam['DebitAmount'] = $amount*-1;
                }
                $api->setParam($setParam);
                $response = $api->push();
                $res = xmlDecode($response, true);
                //print_r($res);
                $afterbalance = $res['Balance'];
                $res_order_id = $res['OrderId'];

                if ($res['ErrorMsgId'] != '0') {
                    $this->resJson['responseStatus']['code'] = 201;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = $res['ErrorMsg'];
                    return $this->returnJson();
                }

                $arrUpdateLog['response_time'] = date('Y-m-d H:i:s');
                $arrUpdateLog['response_data'] = json_encode($this->resJson);
                $arrUpdateLog['credit_af'] = $afterbalance;
                $arrUpdateLog['agcredit_af'] = (string)$agcredit_af;
                $arrUpdateLog['approve_key'] = $approve_key;
                $arrUpdateLog['statement_id'] = $data['stateid'];
                $arrUpdateLog['updated_credit_af'] = date('Y-m-d H:i:s');
                TransfersLog::where('id', $log->id)->update($arrUpdateLog);

                $this->resJson['responseDetails']['requestApprove']['log_id'] = $log->id;

                // If transfer
                if(!empty($entity)) {
                    $repository = \App::make(GenericRepository::class);
                    $repository->setupModel(Jobs::class);
                    // update job
                    $arrUpdate = array(
                        'completed_at' => date('Y-m-d H:i:s'),
                        'statement_id' => $data['stateid'],
                        'status_id' => 3,
                        'order_api_id' => $res_order_id
                    );
                    $repository->updateEntity($arrUpdate, $entity);

                    // update bank statement from trnf.tk
                    BankStatement::where('state_id', $data['stateid'])
                        ->update(['order_id' => $order_code, 'state_status' => '1', 'updated_at' => date('Y-m-d H:i:s')]);

                    $this->resJson['responseDetails']['requestApprove']['tran_id'] = $entity->id;

                }

                $this->resJson['responseDetails']['requestApprove']['approve_key'] = $approve_key;

            }

        }

        /**
         * SBO Live API
         */
        elseif($game == "sbo_old"){

            $api = new SBO($key);

            $user = $api->getUser($username);
            if($user['error']['id'] != 0){
                $this->resJson['responseStatus']['code'] = 203;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = "ไม่มี ".$username." ในเว็บคาสิโน กรุณาสร้างจากระบบใหม่";
                return $this->returnJson();
            }
            $balance = (int)$user['balance'];
            $playing = (int)$user['outstanding'];
            $netbalance = $balance - $playing;

            if($checkwd <= $netbalance){
                $statuswd = 200;
            }

            if($data['type'] == "transfer") {

                if($checkwd > $netbalance && $amount < 0){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "จำนวนเครดิตของท่านไม่พอถอน";
                    return $this->returnJson();
                }

                if($amount > 0 && $amount < 100 && !in_array($order, GameApiController::ACCEPT_MIN_AMOUNT)){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "ฝากขั้นต่ำ 100 บาทขึ้นไป";
                    return $this->returnJson();
                }

                if($amount < 0 && $amount > -100){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "ถอนขั้นต่ำ 100 บาทขึ้นไป";
                    return $this->returnJson();
                }

                /**
                 * Create Log Before Transfer
                 */
                $this->arrLog['job_id'] = $job_id;
                $this->arrLog['order_code'] = $order_code;
                $this->arrLog['amount'] = $amount;
                $this->arrLog['game_id'] = $game_id;
                $this->arrLog['game_code'] = $game;
                $this->arrLog['username_id'] = $username_id;
                $this->arrLog['username'] = $username;
                $this->arrLog['agent_login'] = $aguser;
                $this->arrLog['credit_bf'] = (string)$balance;
                $this->arrLog['agcredit_bf'] = (string)$agcredit_bf;
                $this->arrLog['status'] = 1;
                $log = $this->saveLog($data);

                $setParam = [
                    'amount' => $amount,
                    'username' => $username,
                    'txnId' => (($amount > 0) ? 'IN' : 'OUT') . date('YmdHis') . $username,
                ];
                if($amount > 0){
                    $url_action = "player/deposit.aspx";
                }else{
                    $url_action = "player/withdraw.aspx";
                    $setParam['isFullAmount'] = False;
                    $setParam['amount'] = $amount * -1;
                }
                $api->setParam($setParam, $url_action);
                $res = $api->push();
                $res = json_decode($res, true);

                $afterbalance = $res['balance'];
                $res_order_id = $res['txnId']."_ref".$res['refno'];

                if ($res['error']['id'] != '0') {
                    $this->resJson['responseStatus']['code'] = 201;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = $res['ErrorMsg'];
                    return $this->returnJson();
                }

                $arrUpdateLog['response_time'] = date('Y-m-d H:i:s');
                $arrUpdateLog['response_data'] = json_encode($this->resJson, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_UNICODE);
                $arrUpdateLog['credit_af'] = $afterbalance;
                $arrUpdateLog['agcredit_af'] = (string)$agcredit_af;
                $arrUpdateLog['approve_key'] = $approve_key;
                $arrUpdateLog['statement_id'] = $data['stateid'];
                $arrUpdateLog['updated_credit_af'] = date('Y-m-d H:i:s');
                TransfersLog::where('id', $log->id)->update($arrUpdateLog);

                $this->resJson['responseDetails']['requestApprove']['log_id'] = $log->id;

                // If transfer
                if(!empty($entity)) {
                    $repository = \App::make(GenericRepository::class);
                    $repository->setupModel(Jobs::class);
                    // update job
                    $arrUpdate = array(
                        'completed_at' => date('Y-m-d H:i:s'),
                        'statement_id' => $data['stateid'],
                        'status_id' => 3,
                        'order_api_id' => $res_order_id
                    );
                    $repository->updateEntity($arrUpdate, $entity);

                    // update bank statement from trnf.tk
                    BankStatement::where('state_id', $data['stateid'])
                        ->update(['order_id' => $order_code, 'state_status' => '1', 'updated_at' => date('Y-m-d H:i:s')]);

                    $this->resJson['responseDetails']['requestApprove']['tran_id'] = $entity->id;

                }

                $this->resJson['responseDetails']['requestApprove']['approve_key'] = $approve_key;

            }

        }

        /**
         * Dream Gaming
         */
        elseif($game == "dg"){

            $api = new DG($key);

            $user = $api->getUser($username);

            if($user['codeId'] != 0){
                $this->resJson['responseStatus']['code'] = 203;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = "ไม่มี ".$username." ในเว็บคาสิโน กรุณาสร้างจากระบบใหม่";
                return $this->returnJson();
            }
            $balance = (int)$user['member']['balance'];
            $playing = 0;
            $netbalance = $balance - $playing;

            if($checkwd <= $netbalance){
                $statuswd = 200;
            }

            if($data['type'] == "transfer") {

                if($checkwd > $netbalance && $amount < 0){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "จำนวนเครดิตของท่านไม่พอถอน";
                    return $this->returnJson();
                }

                if($amount > 0 && $amount < 100 && !in_array($order, GameApiController::ACCEPT_MIN_AMOUNT)){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "ฝากขั้นต่ำ 100 บาทขึ้นไป";
                    return $this->returnJson();
                }

                if($amount < 0 && $amount > -100){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "ถอนขั้นต่ำ 100 บาทขึ้นไป";
                    return $this->returnJson();
                }

                /**
                 * Create Log Before Transfer
                 */
                $this->arrLog['job_id'] = $job_id;
                $this->arrLog['order_code'] = $order_code;
                $this->arrLog['amount'] = $amount;
                $this->arrLog['game_id'] = $game_id;
                $this->arrLog['game_code'] = $game;
                $this->arrLog['username_id'] = $username_id;
                $this->arrLog['username'] = $username;
                $this->arrLog['agent_login'] = $aguser;
                $this->arrLog['credit_bf'] = (string)$balance;
                $this->arrLog['agcredit_bf'] = (string)$agcredit_bf;
                $this->arrLog['status'] = 1;
                $log = $this->saveLog($data);

                $setParam = [
                    'member' => [
                        'username' => $username,
                        'amount' => $amount
                    ],
                    'data' => (($amount > 0) ? 'IN' : 'OUT') . date('YmdHis') . $username,
                ];
                if($amount > 0){
                    $url_action = "account/transfer";
                }else{
                    $url_action = "account/transfer";
                    $setParam['member']['amount'] = $amount;
                }
                $api->setParam($setParam, $url_action);
                $res = $api->push();

                $afterbalance = $res['member']['balance'];
                $res_order_id = $res['data'];

                if ($res['codeId'] != '0') {
                    $this->resJson['responseStatus']['code'] = 201;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = $api->getCode($res['codeId']);
                    return $this->returnJson();
                }

                $arrUpdateLog['response_time'] = date('Y-m-d H:i:s');
                $arrUpdateLog['response_data'] = json_encode($this->resJson, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_UNICODE);
                $arrUpdateLog['credit_af'] = $afterbalance;
                $arrUpdateLog['agcredit_af'] = (string)$agcredit_af;
                $arrUpdateLog['approve_key'] = $approve_key;
                $arrUpdateLog['statement_id'] = $data['stateid'];
                $arrUpdateLog['updated_credit_af'] = date('Y-m-d H:i:s');
                TransfersLog::where('id', $log->id)->update($arrUpdateLog);

                $this->resJson['responseDetails']['requestApprove']['log_id'] = $log->id;

                // If transfer
                if(!empty($entity)) {
                    $repository = \App::make(GenericRepository::class);
                    $repository->setupModel(Jobs::class);
                    // update job
                    $arrUpdate = array(
                        'completed_at' => date('Y-m-d H:i:s'),
                        'statement_id' => $data['stateid'],
                        'status_id' => 3,
                        'order_api_id' => $res_order_id
                    );
                    $repository->updateEntity($arrUpdate, $entity);

                    // update bank statement from trnf.tk
                    BankStatement::where('state_id', $data['stateid'])
                        ->update(['order_id' => $order_code, 'state_status' => '1', 'updated_at' => date('Y-m-d H:i:s')]);

                    $this->resJson['responseDetails']['requestApprove']['tran_id'] = $entity->id;

                }

                $this->resJson['responseDetails']['requestApprove']['approve_key'] = $approve_key;

            }

        }

        /**
         * AECBET
         */
        elseif($game == "aec"){

            $api = new AEC($key);

            $user = $api->actionPost(['userName' => $username, 'Act' => 'MB_GET_BALANCE']);

            if($user['error'] != 0){
                $this->resJson['responseStatus']['code'] = 203;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = "ไม่มี ".$username." ในเว็บคาสิโน กรุณาสร้างจากระบบใหม่";
                return $this->returnJson();
            }
            $balance = (int)$user['balance'];
            $playing = 0;
            $netbalance = $balance - $playing;

            if($checkwd <= $netbalance){
                $statuswd = 200;
            }

            if($data['type'] == "transfer") {

                if($checkwd > $netbalance && $amount < 0){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "จำนวนเครดิตของท่านไม่พอถอน";
                    return $this->returnJson();
                }

                if($amount > 0 && $amount < 100 && !in_array($order, GameApiController::ACCEPT_MIN_AMOUNT)){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "ฝากขั้นต่ำ 100 บาทขึ้นไป";
                    return $this->returnJson();
                }

                if($amount < 0 && $amount > -100){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "ถอนขั้นต่ำ 100 บาทขึ้นไป";
                    return $this->returnJson();
                }

                /**
                 * Create Log Before Transfer
                 */
                $this->arrLog['job_id'] = $job_id;
                $this->arrLog['order_code'] = $order_code;
                $this->arrLog['amount'] = $amount;
                $this->arrLog['game_id'] = $game_id;
                $this->arrLog['game_code'] = $game;
                $this->arrLog['username_id'] = $username_id;
                $this->arrLog['username'] = $username;
                $this->arrLog['agent_login'] = $aguser;
                $this->arrLog['credit_bf'] = (string)$balance;
                $this->arrLog['agcredit_bf'] = (string)$agcredit_bf;
                $this->arrLog['status'] = 1;
                $log = $this->saveLog($data);

                $setParam = [
                    'userName' => $username,
                    'amount' => $amount,
                    'remark' => (($amount > 0) ? 'IN' : 'OUT') . date('YmdHis') . $username,
                ];
                if($amount > 0){
                    $setParam['Act'] = "MB_DEPOSIT";
                }else{
                    $setParam['amount'] = $amount * -1;
                    $setParam['Act'] = "MB_WITHDRAW";
                }

                $res = $api->actionPost($setParam);

                $afterbalance = $res['Balance'];
                $res_order_id = $res['paymentId'];

                if ($res['error'] != '0') {
                    $this->resJson['responseStatus']['code'] = 201;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = $res['error'];
                    $this->resJson['responseStatus']['setParam'] = $setParam;
                    $this->resJson['responseStatus']['response'] = $res;
                    return $this->returnJson();
                }

                $arrUpdateLog['response_time'] = date('Y-m-d H:i:s');
                $arrUpdateLog['response_data'] = json_encode($this->resJson, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_UNICODE);
                $arrUpdateLog['credit_af'] = $afterbalance;
                $arrUpdateLog['agcredit_af'] = (string)$agcredit_af;
                $arrUpdateLog['approve_key'] = $approve_key;
                $arrUpdateLog['statement_id'] = $data['stateid'];
                $arrUpdateLog['updated_credit_af'] = date('Y-m-d H:i:s');
                TransfersLog::where('id', $log->id)->update($arrUpdateLog);

                $this->resJson['responseDetails']['requestApprove']['log_id'] = $log->id;

                // If transfer
                if(!empty($entity)) {
                    $repository = \App::make(GenericRepository::class);
                    $repository->setupModel(Jobs::class);
                    // update job
                    $arrUpdate = array(
                        'completed_at' => date('Y-m-d H:i:s'),
                        'statement_id' => $data['stateid'],
                        'status_id' => 3,
                        'order_api_id' => $res_order_id
                    );
                    $repository->updateEntity($arrUpdate, $entity);

                    // update bank statement from trnf.tk
                    BankStatement::where('state_id', $data['stateid'])
                        ->update(['order_id' => $order_code, 'state_status' => '1', 'updated_at' => date('Y-m-d H:i:s')]);

                    $this->resJson['responseDetails']['requestApprove']['tran_id'] = $entity->id;

                }

                $this->resJson['responseDetails']['requestApprove']['approve_key'] = $approve_key;

            }

        }

        /**
         * Sexy Baccarat
         */
        elseif($game == "sexy"){

            $api = new SEXY($key);

            $user = $api->actionGet(['userIds' => $username], 'wallet/getBalance');

            // return $user;

            if($user['status'] != 0000){
                $this->resJson['responseStatus']['code'] = 203;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = $api->getCode($user['status']);
                return $this->returnJson();
            }

            if(!isset($user['results'][$username])){
                $this->resJson['responseStatus']['code'] = 203;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = 'กรุณาตรวจสอบ Username ในเว็บคาศิโน';
                return $this->returnJson();
            }

            $balance = (int)$user['results'][$username];

            $playing = 0;
            $netbalance = $balance - $playing;

            if($checkwd <= $netbalance){
                $statuswd = 200;
            }

            if($data['type'] == "transfer") {

                if($checkwd > $netbalance && $amount < 0){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "จำนวนเครดิตของท่านไม่พอถอน";
                    return $this->returnJson();
                }

                if($amount > 0 && $amount < 100 && !in_array($order, GameApiController::ACCEPT_MIN_AMOUNT)){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "ฝากขั้นต่ำ 100 บาทขึ้นไป";
                    return $this->returnJson();
                }

                if($amount < 0 && $amount > -100){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "ถอนขั้นต่ำ 100 บาทขึ้นไป";
                    return $this->returnJson();
                }

                /**
                 * Create Log Before Transfer
                 */
                $this->arrLog['job_id'] = $job_id;
                $this->arrLog['order_code'] = $order_code;
                $this->arrLog['amount'] = $amount;
                $this->arrLog['game_id'] = $game_id;
                $this->arrLog['game_code'] = $game;
                $this->arrLog['username_id'] = $username_id;
                $this->arrLog['username'] = $username;
                $this->arrLog['agent_login'] = $aguser;
                $this->arrLog['credit_bf'] = (string)$balance;
                $this->arrLog['agcredit_bf'] = (string)$agcredit_bf;
                $this->arrLog['status'] = 1;
                $log = $this->saveLog($data);

                $setParam = [
                    'userId' => $username,
                    'txCode' => (($amount > 0) ? 'IN' : 'OUT') . date('YmdHis') . $username,
                ];
                if($amount > 0){
                    $url = "wallet/deposit";
                    $setParam['transferAmount'] = $amount;
                }else{
                    $url = "wallet/withdraw";
                    $setParam['transferAmount'] = $amount * -1;
                    $setParam['withdrawType'] = 0; // is partial
                }

                $res = $api->actionGet($setParam, $url);

                if ($res['status'] != '0000') {
                    $this->resJson['responseStatus']['code'] = 201;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = $api->getCode($res['status']);
                    $this->resJson['responseStatus']['setParam'] = $setParam;
                    return $this->returnJson();
                }

                $afterbalance = $res['currentBalance'];
                $res_order_id = $res['txCode'];

                $arrUpdateLog['response_time'] = date('Y-m-d H:i:s');
                $arrUpdateLog['response_data'] = json_encode($this->resJson, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_UNICODE);
                $arrUpdateLog['credit_af'] = $afterbalance;
                $arrUpdateLog['agcredit_af'] = (string)$agcredit_af;
                $arrUpdateLog['approve_key'] = $approve_key;
                $arrUpdateLog['statement_id'] = $data['stateid'];
                $arrUpdateLog['updated_credit_af'] = date('Y-m-d H:i:s');
                TransfersLog::where('id', $log->id)->update($arrUpdateLog);

                $this->resJson['responseDetails']['requestApprove']['log_id'] = $log->id;

                // If transfer
                if(!empty($entity)) {
                    $repository = \App::make(GenericRepository::class);
                    $repository->setupModel(Jobs::class);
                    // update job
                    $arrUpdate = array(
                        'completed_at' => date('Y-m-d H:i:s'),
                        'statement_id' => $data['stateid'],
                        'status_id' => 3,
                        'order_api_id' => $res_order_id
                    );
                    $repository->updateEntity($arrUpdate, $entity);

                    // update bank statement from trnf.tk
                    BankStatement::where('state_id', $data['stateid'])
                        ->update(['order_id' => $order_code, 'state_status' => '1', 'updated_at' => date('Y-m-d H:i:s')]);

                    $this->resJson['responseDetails']['requestApprove']['tran_id'] = $entity->id;

                }

                $this->resJson['responseDetails']['requestApprove']['approve_key'] = $approve_key;

            }

        }

        /**
         * IBC SportsBook
         */
        elseif($game == "ibc"){

            $api = new IBC($key);

            $arrUsername = [
                'password' => $password,
                'providercode' => 'IB',
                'username' => $username
            ];
            $user = $api->actionGet($arrUsername, 'getBalance.aspx');

            if($user['errCode'] != 0){
                $this->resJson['responseStatus']['code'] = 203;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = $user['errMsg'];
                return $this->returnJson();
            }
            $balance = (int)$user['balance'];
            $playing = 0;
            $netbalance = $balance - $playing;

            if($checkwd <= $netbalance){
                $statuswd = 200;
            }

            if($data['type'] == "transfer") {

                if($checkwd > $netbalance && $amount < 0){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "จำนวนเครดิตของท่านไม่พอถอน";
                    return $this->returnJson();
                }

                if($amount > 0 && $amount < 100 && !in_array($order, GameApiController::ACCEPT_MIN_AMOUNT)){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "ฝากขั้นต่ำ 100 บาทขึ้นไป";
                    return $this->returnJson();
                }

                if($amount < 0 && $amount > -100 && !in_array($order, GameApiController::ACCEPT_MIN_AMOUNT)){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "ถอนขั้นต่ำ 100 บาทขึ้นไป";
                    return $this->returnJson();
                }

                /**
                 * Create Log Before Transfer
                 */
                $this->arrLog['job_id'] = $job_id;
                $this->arrLog['order_code'] = $order_code;
                $this->arrLog['amount'] = $amount;
                $this->arrLog['game_id'] = $game_id;
                $this->arrLog['game_code'] = $game;
                $this->arrLog['username_id'] = $username_id;
                $this->arrLog['username'] = $username;
                $this->arrLog['agent_login'] = $aguser;
                $this->arrLog['credit_bf'] = (string)$balance;
                $this->arrLog['agcredit_bf'] = (string)$agcredit_bf;
                $this->arrLog['status'] = 1;
                $log = $this->saveLog($data);

                $ref = time() ."_". $username_id;

                $setParam = [
                    'password' => $password,
                    'providercode' => 'IB',
                    'referenceid' => $ref,
                ];
                if($amount > 0){
                    $url = "makeTransfer.aspx";
                    $setParam['type'] = 0;
                }else{
                    $url = "makeTransfer.aspx";
                    $setParam['type'] = 1;
                    $amount = $amount * -1;
                }
                $setParam['username'] = $username;

                $res = $api->actionGet($setParam, $url, ['amount' => $amount], []);

                if ($res['errCode'] != 0) {
                    $this->resJson['responseStatus']['code'] = 201;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = $res['errMsg'];
                    $this->resJson['responseStatus']['setParam'] = $setParam;
                    return $this->returnJson();
                }

                $arrUsername = [
                    'password' => $password,
                    'providercode' => 'IB',
                    'username' => $username
                ];
                $user = $api->actionGet($arrUsername, 'getBalance.aspx');

                $afterbalance = $user['balance'];
                $res_order_id = $ref;

                $arrUpdateLog['response_time'] = date('Y-m-d H:i:s');
                $arrUpdateLog['response_data'] = json_encode($this->resJson, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_UNICODE);
                $arrUpdateLog['credit_af'] = $afterbalance;
                $arrUpdateLog['agcredit_af'] = (string)$agcredit_af;
                $arrUpdateLog['approve_key'] = $approve_key;
                $arrUpdateLog['statement_id'] = $data['stateid'];
                $arrUpdateLog['updated_credit_af'] = date('Y-m-d H:i:s');
                TransfersLog::where('id', $log->id)->update($arrUpdateLog);

                $this->resJson['responseDetails']['requestApprove']['log_id'] = $log->id;

                // If transfer
                if(!empty($entity)) {
                    $repository = \App::make(GenericRepository::class);
                    $repository->setupModel(Jobs::class);
                    // update job
                    $arrUpdate = array(
                        'completed_at' => date('Y-m-d H:i:s'),
                        'statement_id' => $data['stateid'],
                        'status_id' => 3,
                        'order_api_id' => $res_order_id
                    );
                    $repository->updateEntity($arrUpdate, $entity);

                    // update bank statement from trnf.tk
                    BankStatement::where('state_id', $data['stateid'])
                        ->update(['order_id' => $order_code, 'state_status' => '1', 'updated_at' => date('Y-m-d H:i:s')]);

                    $this->resJson['responseDetails']['requestApprove']['tran_id'] = $entity->id;

                }

                $this->resJson['responseDetails']['requestApprove']['approve_key'] = $approve_key;

            }

        }

        /**
         * LottoSH
         */
        elseif($game == "lottosh"){

            $api = new LOTTO($key);

            $arrUsername = [
                'username' => $username
            ];
            $user = $api->actionPost($arrUsername, 'balance');

            if($user['status'] != 'success'){
                $this->resJson['responseStatus']['code'] = 203;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = $user['msg'];
                return $this->returnJson();
            }
            $balance = (int)$user['balance'];
            $playing = 0;
            $netbalance = $balance - $playing;

            if($checkwd <= $netbalance){
                $statuswd = 200;
            }

            if($data['type'] == "transfer") {

                if($checkwd > $netbalance && $amount < 0){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "จำนวนเครดิตของท่านไม่พอถอน";
                    return $this->returnJson();
                }

                if($amount > 0 && $amount < 100 && !in_array($order, GameApiController::ACCEPT_MIN_AMOUNT)){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "ฝากขั้นต่ำ 100 บาทขึ้นไป";
                    return $this->returnJson();
                }

                if($amount < 0 && $amount > -100 && !in_array($order, GameApiController::ACCEPT_MIN_AMOUNT)){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "ถอนขั้นต่ำ 100 บาทขึ้นไป";
                    return $this->returnJson();
                }

                /**
                 * Create Log Before Transfer
                 */
                $this->arrLog['job_id'] = $job_id;
                $this->arrLog['order_code'] = $order_code;
                $this->arrLog['amount'] = $amount;
                $this->arrLog['game_id'] = $game_id;
                $this->arrLog['game_code'] = $game;
                $this->arrLog['username_id'] = $username_id;
                $this->arrLog['username'] = $username;
                $this->arrLog['agent_login'] = $aguser;
                $this->arrLog['credit_bf'] = (string)$balance;
                $this->arrLog['agcredit_bf'] = (string)$agcredit_bf;
                $this->arrLog['status'] = 1;
                $log = $this->saveLog($data);

                $setParam = [
                    'username' => $username
                ];
                if($amount > 0){
                    $setParam['type'] = 'deposit';
                    $ref = "IN".date('YmdHis').$username_id;
                }else{
                    $setParam['type'] = 'withdraw';
                    $amount = $amount * -1;
                    $ref = "OUT".date('YmdHis').$username_id;
                }
                $setParam['ref'] = $ref;
                $setParam['amount'] = $amount;

                $res = $api->actionPost($setParam, 'transfer');

                if ($res['status'] != 'success') {
                    $this->resJson['responseStatus']['code'] = 201;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = $res['msg'];
                    $this->resJson['responseStatus']['setParam'] = $setParam;
                    $this->resJson['responseStatus']['response'] = $res;
                    return $this->returnJson();
                }

                $afterbalance = $res['after_credit'];
                $res_order_id = $ref;

                $arrUpdateLog['response_time'] = date('Y-m-d H:i:s');
                $arrUpdateLog['response_data'] = json_encode($this->resJson, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_UNICODE);
                $arrUpdateLog['credit_af'] = $afterbalance;
                $arrUpdateLog['agcredit_af'] = (string)$agcredit_af;
                $arrUpdateLog['approve_key'] = $approve_key;
                $arrUpdateLog['statement_id'] = $data['stateid'];
                $arrUpdateLog['updated_credit_af'] = date('Y-m-d H:i:s');
                TransfersLog::where('id', $log->id)->update($arrUpdateLog);

                $this->resJson['responseDetails']['requestApprove']['log_id'] = $log->id;

                // If transfer
                if(!empty($entity)) {
                    $repository = \App::make(GenericRepository::class);
                    $repository->setupModel(Jobs::class);
                    // update job
                    $arrUpdate = array(
                        'completed_at' => date('Y-m-d H:i:s'),
                        'statement_id' => $data['stateid'],
                        'status_id' => 3,
                        'order_api_id' => $res_order_id
                    );
                    $repository->updateEntity($arrUpdate, $entity);

                    // update bank statement from trnf.tk
                    BankStatement::where('state_id', $data['stateid'])
                        ->update(['order_id' => $order_code, 'state_status' => '1', 'updated_at' => date('Y-m-d H:i:s')]);

                    $this->resJson['responseDetails']['requestApprove']['tran_id'] = $entity->id;

                }

                $this->resJson['responseDetails']['requestApprove']['approve_key'] = $approve_key;

            }

        }

        /**
         * Tiger24
         */
        elseif($game == "tiger"){

            $api = new TIGER($key);

            $arrUsername = [
                'search_code' => $username,
                'item_per_page' => 1,
                'page' => 1
            ];
            $user = $api->actionPost($arrUsername, 'view_member_api.php');

            if(count($user) < 2){
                $this->resJson['responseStatus']['code'] = 203;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = 'กรุณาตรวสอบ Username ไม่มีในระบบ';
                return $this->returnJson();
            }

            $user = $user[1];

            if($user['errcode'] != '00003'){
                $this->resJson['responseStatus']['code'] = 203;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = 'ไม่สามารถดึง Balance ได้';
                return $this->returnJson();
            }

            $balance = (int)$user['credit_remain'];
            $playing = (int)$user['play_amount'];
            $netbalance = $balance - $playing;

            if($checkwd <= $netbalance){
                $statuswd = 200;
            }

            if($data['type'] == "transfer") {

                if($checkwd > $netbalance && $amount < 0){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "จำนวนเครดิตของท่านไม่พอถอน";
                    return $this->returnJson();
                }

                if($amount > 0 && $amount < 100 && !in_array($order, GameApiController::ACCEPT_MIN_AMOUNT)){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "ฝากขั้นต่ำ 100 บาทขึ้นไป";
                    return $this->returnJson();
                }

                if($amount < 0 && $amount > -100 && !in_array($order, GameApiController::ACCEPT_MIN_AMOUNT)){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "ถอนขั้นต่ำ 100 บาทขึ้นไป";
                    return $this->returnJson();
                }

                /**
                 * Create Log Before Transfer
                 */
                $this->arrLog['job_id'] = $job_id;
                $this->arrLog['order_code'] = $order_code;
                $this->arrLog['amount'] = $amount;
                $this->arrLog['game_id'] = $game_id;
                $this->arrLog['game_code'] = $game;
                $this->arrLog['username_id'] = $username_id;
                $this->arrLog['username'] = $username;
                $this->arrLog['agent_login'] = $aguser;
                $this->arrLog['credit_bf'] = (string)$balance;
                $this->arrLog['agcredit_bf'] = (string)$agcredit_bf;
                $this->arrLog['status'] = 1;
                $log = $this->saveLog($data);

                $setParam = [
                    'user_member' => $username
                ];
                if($amount > 0){
                    $ref = "IN".date('YmdHis').$username_id;
                }else{
                    $ref = "OUT".date('YmdHis').$username_id;
                }
                $setParam['amount_transfer'] = $amount;

                $res = $api->actionPost($setParam, 'tranfer_api.php');

                if ($res['errcode'] != '00008') {
                    $this->resJson['responseStatus']['code'] = 201;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = $res['errtext'];
                    $this->resJson['responseStatus']['setParam'] = $setParam;
                    $this->resJson['responseStatus']['response'] = $res;
                    return $this->returnJson();
                }

                $afterbalance = $res['balance'];
                $res_order_id = $ref;

                $arrUpdateLog['response_time'] = date('Y-m-d H:i:s');
                $arrUpdateLog['response_data'] = json_encode($this->resJson, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_UNICODE);
                $arrUpdateLog['credit_af'] = $afterbalance;
                $arrUpdateLog['agcredit_af'] = (string)$agcredit_af;
                $arrUpdateLog['approve_key'] = $approve_key;
                $arrUpdateLog['statement_id'] = $data['stateid'];
                $arrUpdateLog['updated_credit_af'] = date('Y-m-d H:i:s');
                TransfersLog::where('id', $log->id)->update($arrUpdateLog);

                $this->resJson['responseDetails']['requestApprove']['log_id'] = $log->id;

                // If transfer
                if(!empty($entity)) {
                    $repository = \App::make(GenericRepository::class);
                    $repository->setupModel(Jobs::class);
                    // update job
                    $arrUpdate = array(
                        'completed_at' => date('Y-m-d H:i:s'),
                        'statement_id' => $data['stateid'],
                        'status_id' => 3,
                        'order_api_id' => $res_order_id
                    );
                    $repository->updateEntity($arrUpdate, $entity);

                    // update bank statement from trnf.tk
                    BankStatement::where('state_id', $data['stateid'])
                        ->update(['order_id' => $order_code, 'state_status' => '1', 'updated_at' => date('Y-m-d H:i:s')]);

                    $this->resJson['responseDetails']['requestApprove']['tran_id'] = $entity->id;

                }

                $this->resJson['responseDetails']['requestApprove']['approve_key'] = $approve_key;

            }

        }

        /**
         * SBOBET API
         */
        elseif($game == "sboapi"){

            $api = new SBO($key);

            $arrUsername = [
                'Username' => $username
            ];
            $user = $api->actionPost($arrUsername, 'web-root/restricted/player/get-player-balance.aspx');

            if($user['error']['id'] != 0){
                $this->resJson['responseStatus']['code'] = 203;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = $user['error']['msg'];
                return $this->returnJson();
            }

            $balance = (int)$user['balance'];
            $playing = (int)$user['outstanding'];
            $netbalance = $balance - $playing;

            if($checkwd <= $netbalance){
                $statuswd = 200;
            }

            if($data['type'] == "transfer") {

                if($checkwd > $netbalance && $amount < 0){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "จำนวนเครดิตของท่านไม่พอถอน";
                    return $this->returnJson();
                }

                if($amount > 0 && $amount < 100 && !in_array($order, GameApiController::ACCEPT_MIN_AMOUNT)){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "ฝากขั้นต่ำ 100 บาทขึ้นไป";
                    return $this->returnJson();
                }

                if($amount < 0 && $amount > -100 && !in_array($order, GameApiController::ACCEPT_MIN_AMOUNT)){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "ถอนขั้นต่ำ 100 บาทขึ้นไป";
                    return $this->returnJson();
                }

                /**
                 * Create Log Before Transfer
                 */
                $this->arrLog['job_id'] = $job_id;
                $this->arrLog['order_code'] = $order_code;
                $this->arrLog['amount'] = $amount;
                $this->arrLog['game_id'] = $game_id;
                $this->arrLog['game_code'] = $game;
                $this->arrLog['username_id'] = $username_id;
                $this->arrLog['username'] = $username;
                $this->arrLog['agent_login'] = $aguser;
                $this->arrLog['credit_bf'] = (string)$balance;
                $this->arrLog['agcredit_bf'] = (string)$agcredit_bf;
                $this->arrLog['status'] = 1;
                $log = $this->saveLog($data);

                $setParam = [
                    'Username' => $username,
                    'Amount' => $amount
                ];
                if($amount > 0){
                    $url = "web-root/restricted/player/deposit.aspx";
                    $ref = "IN".date('YmdHis').$username.rand12(3);
                }else{
                    $url = "web-root/restricted/player/withdraw.aspx";
                    $ref = "OUT".date('YmdHis').$username.rand12(3);
                    $setParam['IsFullAmount'] = false;
                    $setParam['Amount'] = $amount * -1;
                }

                if(isset($data['refno'])){
                    $ref = $data['refno'];
                }

                $setParam['TxnId'] = $ref;

                $res = $api->actionPost($setParam, $url);

                if ($res['error']['id'] != 0) {
                    $this->resJson['responseStatus']['code'] = 201;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = $res['error']['msg'];
                    $this->resJson['responseStatus']['setParam'] = $setParam;
                    $this->resJson['responseStatus']['response'] = $res;
                    return $this->returnJson();
                }

                $afterbalance = $res['balance'];
                $res_order_id = $ref;

                $arrUpdateLog['response_time'] = date('Y-m-d H:i:s');
                $arrUpdateLog['response_data'] = json_encode($this->resJson, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_UNICODE);
                $arrUpdateLog['credit_af'] = $afterbalance;
                $arrUpdateLog['agcredit_af'] = (string)$agcredit_af;
                $arrUpdateLog['approve_key'] = $approve_key;
                $arrUpdateLog['statement_id'] = $data['stateid'];
                $arrUpdateLog['updated_credit_af'] = date('Y-m-d H:i:s');
                TransfersLog::where('id', $log->id)->update($arrUpdateLog);

                $this->resJson['responseDetails']['requestApprove']['log_id'] = $log->id;

                // If transfer
                if(!empty($entity)) {
                    $repository = \App::make(GenericRepository::class);
                    $repository->setupModel(Jobs::class);
                    // update job
                    $arrUpdate = array(
                        'completed_at' => date('Y-m-d H:i:s'),
                        'statement_id' => $data['stateid'],
                        'status_id' => 3,
                        'order_api_id' => $res_order_id
                    );
                    $repository->updateEntity($arrUpdate, $entity);

                    // update bank statement from trnf.tk
                    BankStatement::where('state_id', $data['stateid'])
                        ->update(['order_id' => $order_code, 'state_status' => '1', 'updated_at' => date('Y-m-d H:i:s')]);

                    $this->resJson['responseDetails']['requestApprove']['tran_id'] = $entity->id;

                }

                $this->resJson['responseDetails']['requestApprove']['approve_key'] = $approve_key;

            }

        }

        /**
         * Casino SH API
         */
        elseif($game == "csh"){

            $api = new CSH($key);

            $m_user = Username::findOrFail($username_id);

            if(empty($m_user->ref_id)){
                $this->resJson['responseStatus']['code'] = 203;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = "ไม่มี Ref ID สำหรับ Username นี้";
                return $this->returnJson();
            }

            $arrUsername = [
                'id' => $m_user->ref_id
            ];
            $user = $api->actionPost($arrUsername, 'balance');

            if($user['status'] != "success"){
                $this->resJson['responseStatus']['code'] = 203;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = $user['msg'];
                return $this->returnJson();
            }

            $balance = (float)$user['balance'];
            $playing = 0;
            $netbalance = $balance - $playing;

            if($checkwd <= $netbalance){
                $statuswd = 200;
            }

            if($data['type'] == "transfer") {

                if($checkwd > $netbalance && $amount < 0){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "จำนวนเครดิตของท่านไม่พอถอน";
                    return $this->returnJson();
                }

                if($amount > 0 && $amount < 100 && !in_array($order, GameApiController::ACCEPT_MIN_AMOUNT)){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "ฝากขั้นต่ำ 100 บาทขึ้นไป";
                    return $this->returnJson();
                }

                if($amount < 0 && $amount > -100 && !in_array($order, GameApiController::ACCEPT_MIN_AMOUNT)){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "ถอนขั้นต่ำ 100 บาทขึ้นไป";
                    return $this->returnJson();
                }

                /**
                 * Create Log Before Transfer
                 */
                $this->arrLog['job_id'] = $job_id;
                $this->arrLog['order_code'] = $order_code;
                $this->arrLog['amount'] = $amount;
                $this->arrLog['game_id'] = $game_id;
                $this->arrLog['game_code'] = $game;
                $this->arrLog['username_id'] = $username_id;
                $this->arrLog['username'] = $username;
                $this->arrLog['agent_login'] = $aguser;
                $this->arrLog['credit_bf'] = (string)$balance;
                $this->arrLog['agcredit_bf'] = (string)$agcredit_bf;
                $this->arrLog['status'] = 1;
                $log = $this->saveLog($data);

                $setParam = [
                    'id' => $m_user->ref_id,
                    'amount' => $amount
                ];
                if($amount > 0){
                    $url = "credit_p";
                    $ref = "IN".date('YmdHis').$username;
                }else{
                    $url = "credit_p";
                    $ref = "OUT".date('YmdHis').$username;
                }
                $setParam['refID'] = $ref;

                $res = $api->actionPost($setParam, $url);

                if ($res['status'] != 'success') {
                    $this->resJson['responseStatus']['code'] = 201;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = $res['msg'];
                    $this->resJson['responseStatus']['setParam'] = $setParam;
                    $this->resJson['responseStatus']['response'] = $res;
                    return $this->returnJson();
                }

                $afterbalance = $res['balance'];
                $res_order_id = $ref;

                $arrUpdateLog['response_time'] = date('Y-m-d H:i:s');
                $arrUpdateLog['response_data'] = json_encode($this->resJson, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_UNICODE);
                $arrUpdateLog['credit_af'] = $afterbalance;
                $arrUpdateLog['agcredit_af'] = (string)$agcredit_af;
                $arrUpdateLog['approve_key'] = $approve_key;
                $arrUpdateLog['statement_id'] = $data['stateid'];
                $arrUpdateLog['updated_credit_af'] = date('Y-m-d H:i:s');
                TransfersLog::where('id', $log->id)->update($arrUpdateLog);

                $this->resJson['responseDetails']['requestApprove']['log_id'] = $log->id;

                // If transfer
                if(!empty($entity)) {
                    $repository = \App::make(GenericRepository::class);
                    $repository->setupModel(Jobs::class);
                    // update job
                    $arrUpdate = array(
                        'completed_at' => date('Y-m-d H:i:s'),
                        'statement_id' => $data['stateid'],
                        'status_id' => 3,
                        'order_api_id' => $res_order_id
                    );
                    $repository->updateEntity($arrUpdate, $entity);

                    // update bank statement from trnf.tk
                    BankStatement::where('state_id', $data['stateid'])
                        ->update(['order_id' => $order_code, 'state_status' => '1', 'updated_at' => date('Y-m-d H:i:s')]);

                    $this->resJson['responseDetails']['requestApprove']['tran_id'] = $entity->id;

                }

                $this->resJson['responseDetails']['requestApprove']['approve_key'] = $approve_key;

            }

        }

        /**
         * Pussy888
         */
        elseif($game == "pussy"){

            $api = new PUSSY($key);

            $m_user = Username::findOrFail($username_id);

            if(empty($m_user->ref_id)){
                $this->resJson['responseStatus']['code'] = 203;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = "ไม่มี Ref ID สำหรับ Username นี้";
                return $this->returnJson();
            }

            $arrUsername = [
                'action' => 'getUserInfo',
                'userName' => $m_user->ref_id
            ];
            $user = $api->actionGet($arrUsername, 'ashx/account/account.ashx');

            if(!$user['success']){
                $this->resJson['responseStatus']['code'] = 203;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = $user['msg'];
                return $this->returnJson();
            }

            $balance = (int)$user['MoneyNum'];
            $playing = 0;
            $netbalance = $balance - $playing;

            if($checkwd <= $netbalance){
                $statuswd = 200;
            }

            if($data['type'] == "transfer") {

                if($checkwd > $netbalance && $amount < 0){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "จำนวนเครดิตของท่านไม่พอถอน";
                    return $this->returnJson();
                }

                if($amount > 0 && $amount < 100 && !in_array($order, GameApiController::ACCEPT_MIN_AMOUNT)){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "ฝากขั้นต่ำ 100 บาทขึ้นไป";
                    return $this->returnJson();
                }

                if($amount < 0 && $amount > -100 && !in_array($order, GameApiController::ACCEPT_MIN_AMOUNT)){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "ถอนขั้นต่ำ 100 บาทขึ้นไป";
                    return $this->returnJson();
                }

                /**
                 * Create Log Before Transfer
                 */
                $this->arrLog['job_id'] = $job_id;
                $this->arrLog['order_code'] = $order_code;
                $this->arrLog['amount'] = $amount;
                $this->arrLog['game_id'] = $game_id;
                $this->arrLog['game_code'] = $game;
                $this->arrLog['username_id'] = $username_id;
                $this->arrLog['username'] = $username;
                $this->arrLog['agent_login'] = $aguser;
                $this->arrLog['credit_bf'] = (string)$balance;
                $this->arrLog['agcredit_bf'] = (string)$agcredit_bf;
                $this->arrLog['status'] = 1;
                $log = $this->saveLog($data);

                $setParam = [
                    'action' => 'setServerScore',
                    'userName' => $m_user->ref_id,
                    'scoreNum' => $amount,
                    'ActionUser' => $username,
                    'ActionIp' => get_client_ip(),
                ];
                if($amount > 0){
                    $url = "ashx/account/setScore.ashx";
                    $ref = "IN".date('YmdHis').$username;
                }else{
                    $url = "ashx/account/setScore.ashx";
                    $ref = "OUT".date('YmdHis').$username;
                }

                $res = $api->actionGet($setParam, $url);

                if (!$res['success']) {
                    $this->resJson['responseStatus']['code'] = 201;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = $res['msg'];
                    $this->resJson['responseStatus']['setParam'] = $setParam;
                    $this->resJson['responseStatus']['response'] = $res;
                    return $this->returnJson();
                }

                $afterbalance = $res['money'];
                $res_order_id = $ref;

                $arrUpdateLog['response_time'] = date('Y-m-d H:i:s');
                $arrUpdateLog['response_data'] = json_encode($this->resJson, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_UNICODE);
                $arrUpdateLog['credit_af'] = $afterbalance;
                $arrUpdateLog['agcredit_af'] = (string)$agcredit_af;
                $arrUpdateLog['approve_key'] = $approve_key;
                $arrUpdateLog['statement_id'] = $data['stateid'];
                $arrUpdateLog['updated_credit_af'] = date('Y-m-d H:i:s');
                TransfersLog::where('id', $log->id)->update($arrUpdateLog);

                $this->resJson['responseDetails']['requestApprove']['log_id'] = $log->id;

                // If transfer
                if(!empty($entity)) {
                    $repository = \App::make(GenericRepository::class);
                    $repository->setupModel(Jobs::class);
                    // update job
                    $arrUpdate = array(
                        'completed_at' => date('Y-m-d H:i:s'),
                        'statement_id' => $data['stateid'],
                        'status_id' => 3,
                        'order_api_id' => $res_order_id
                    );
                    $repository->updateEntity($arrUpdate, $entity);

                    // update bank statement from trnf.tk
                    BankStatement::where('state_id', $data['stateid'])
                        ->update(['order_id' => $order_code, 'state_status' => '1', 'updated_at' => date('Y-m-d H:i:s')]);

                    $this->resJson['responseDetails']['requestApprove']['tran_id'] = $entity->id;

                }

                $this->resJson['responseDetails']['requestApprove']['approve_key'] = $approve_key;

            }

        }

        /**
         * TFGaming
         */
        elseif($game == "tfg"){

            $api = new TFG($key);

            $arrUsername = [
                'LoginName' => $username
            ];
            $user = $api->actionGet($arrUsername, 'balance/');

            if($user['count'] == 0){

                if($data['type'] == "detail"){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "ไม่มี Username นี้ในระบบ";
                    return $this->returnJson();
                }

                $balance = 0;
            }else{
                $user = $user['results'][0];
                $balance = (int)$user['balance'];
            }

            $playing = 0;
            $netbalance = $balance - $playing;

            if($checkwd <= $netbalance){
                $statuswd = 200;
            }

            if($data['type'] == "transfer") {

                if($checkwd > $netbalance && $amount < 0){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "จำนวนเครดิตของท่านไม่พอถอน";
                    return $this->returnJson();
                }

                if($amount > 0 && $amount < 100 && !in_array($order, GameApiController::ACCEPT_MIN_AMOUNT)){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "ฝากขั้นต่ำ 100 บาทขึ้นไป";
                    return $this->returnJson();
                }

                if($amount < 0 && $amount > -100 && !in_array($order, GameApiController::ACCEPT_MIN_AMOUNT)){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "ถอนขั้นต่ำ 100 บาทขึ้นไป";
                    return $this->returnJson();
                }

                /**
                 * Create Log Before Transfer
                 */
                $this->arrLog['job_id'] = $job_id;
                $this->arrLog['order_code'] = $order_code;
                $this->arrLog['amount'] = $amount;
                $this->arrLog['game_id'] = $game_id;
                $this->arrLog['game_code'] = $game;
                $this->arrLog['username_id'] = $username_id;
                $this->arrLog['username'] = $username;
                $this->arrLog['agent_login'] = $aguser;
                $this->arrLog['credit_bf'] = (string)$balance;
                $this->arrLog['agcredit_bf'] = (string)$agcredit_bf;
                $this->arrLog['status'] = 1;
                $log = $this->saveLog($data);

                $setParam = [
                    'member' => $username,
                    'operator_id' => $api->operator_id,
                    'amount' => $amount
                ];
                if($amount > 0){
                    $url = "deposit/";
                    $ref = "IN".date('YmdHis').$username;
                }else{
                    $url = "withdraw/";
                    $ref = "OUT".date('YmdHis').$username;
                    $setParam['amount'] = $amount * -1;
                }

                $setParam['reference_no'] = $ref;

                $res = $api->actionPost($setParam, $url);

                if (isset($res['errors'])) {
                    $this->resJson['responseStatus']['code'] = 201;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = json_encode($res['errors']);
                    $this->resJson['responseStatus']['setParam'] = $setParam;
                    $this->resJson['responseStatus']['response'] = $res;
                    return $this->returnJson();
                }

                $afterbalance = $res['balance_amount'];
                $res_order_id = $ref;

                $arrUpdateLog['response_time'] = date('Y-m-d H:i:s');
                $arrUpdateLog['response_data'] = json_encode($this->resJson, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_UNICODE);
                $arrUpdateLog['credit_af'] = $afterbalance;
                $arrUpdateLog['agcredit_af'] = (string)$agcredit_af;
                $arrUpdateLog['approve_key'] = $approve_key;
                $arrUpdateLog['statement_id'] = $data['stateid'];
                $arrUpdateLog['updated_credit_af'] = date('Y-m-d H:i:s');
                TransfersLog::where('id', $log->id)->update($arrUpdateLog);

                $this->resJson['responseDetails']['requestApprove']['log_id'] = $log->id;

                // If transfer
                if(!empty($entity)) {
                    $repository = \App::make(GenericRepository::class);
                    $repository->setupModel(Jobs::class);
                    // update job
                    $arrUpdate = array(
                        'completed_at' => date('Y-m-d H:i:s'),
                        'statement_id' => $data['stateid'],
                        'status_id' => 3,
                        'order_api_id' => $res_order_id
                    );
                    $repository->updateEntity($arrUpdate, $entity);

                    // update bank statement from trnf.tk
                    BankStatement::where('state_id', $data['stateid'])
                        ->update(['order_id' => $order_code, 'state_status' => '1', 'updated_at' => date('Y-m-d H:i:s')]);

                    $this->resJson['responseDetails']['requestApprove']['tran_id'] = $entity->id;

                }

                $this->resJson['responseDetails']['requestApprove']['approve_key'] = $approve_key;

            }

        }

        /**
         * Ufabet
         */
        elseif(in_array($game, ['ufa', 'lga', 'gcb', 'vga', 'hol'])){

            $api = new TRNF($key);

            $ag_data = $user['username_board']['users_board'][0];
            $api->setAgentUser($ag_data);

            $arrUsername = [
                'type' => 'detail',
                'custid' => $username,
                'game' => $game
            ];

            $user = $api->actionPost($arrUsername, $game.'/');

            if($user['responseStatus']['code'] != 200){
                $this->resJson['responseStatus']['code'] = 203;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = $user['responseStatus']['messageDetails'];
                return $this->returnJson();
            }

            $detail = $user['responseDetails'];

            $balance = (float)$detail['banlance'];
            $playing = (float)$detail['playing'];
            $netbalance = $balance - $playing;

            if($checkwd <= $netbalance){
                $statuswd = 200;
            }

            if($data['type'] == "transfer") {

                if($checkwd > $netbalance && $amount < 0){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "จำนวนเครดิตของท่านไม่พอถอน";
                    return $this->returnJson();
                }

                if($amount > 0 && $amount < 100 && !in_array($order, GameApiController::ACCEPT_MIN_AMOUNT)){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "ฝากขั้นต่ำ 100 บาทขึ้นไป";
                    return $this->returnJson();
                }

                if($amount < 0 && $amount > -100 && !in_array($order, GameApiController::ACCEPT_MIN_AMOUNT)){
                    $this->resJson['responseStatus']['code'] = 203;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = "ถอนขั้นต่ำ 100 บาทขึ้นไป";
                    return $this->returnJson();
                }

                /**
                 * Create Log Before Transfer
                 */
                $this->arrLog['job_id'] = $job_id;
                $this->arrLog['order_code'] = $order_code;
                $this->arrLog['amount'] = $amount;
                $this->arrLog['game_id'] = $game_id;
                $this->arrLog['game_code'] = $game;
                $this->arrLog['username_id'] = $username_id;
                $this->arrLog['username'] = $username;
                $this->arrLog['agent_login'] = $aguser;
                $this->arrLog['credit_bf'] = (string)$balance;
                $this->arrLog['agcredit_bf'] = (string)$agcredit_bf;
                $this->arrLog['status'] = 1;
                $log = $this->saveLog($data);

                $setParam = [
                    'type' => 'transfer',
                    'custid' => $username,
                    'amount' => $amount,
                    'orderid' => $data['orderid'],
                    'stateid' => $data['stateid'],
                    'game' => $game
                ];

                $res = $api->actionPost($setParam, $game.'/');

                if ($res['responseStatus']['code'] != 200) {
                    $this->resJson['responseStatus']['code'] = 201;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = $res['responseStatus']['messageDetails'];
                    $this->resJson['responseStatus']['setParam'] = $setParam;
                    $this->resJson['responseStatus']['response'] = $res;
                    return $this->returnJson();
                }

                $detail = $res['responseDetails'];

                $afterbalance = $detail['afterbanlance'];
                // $res_order_id = $ref;

                $arrUpdateLog['response_time'] = date('Y-m-d H:i:s');
                $arrUpdateLog['response_data'] = json_encode($this->resJson, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_UNICODE);
                $arrUpdateLog['credit_af'] = $afterbalance;
                $arrUpdateLog['agcredit_af'] = (string)$agcredit_af;
                $arrUpdateLog['approve_key'] = $approve_key;
                $arrUpdateLog['statement_id'] = $data['stateid'];
                $arrUpdateLog['updated_credit_af'] = date('Y-m-d H:i:s');
                TransfersLog::where('id', $log->id)->update($arrUpdateLog);

                $this->resJson['responseDetails']['requestApprove']['log_id'] = $log->id;

                // If transfer
                if(!empty($entity)) {
                    $repository = \App::make(GenericRepository::class);
                    $repository->setupModel(Jobs::class);
                    // update job
                    $arrUpdate = array(
                        'completed_at' => date('Y-m-d H:i:s'),
                        'statement_id' => $data['stateid'],
                        'status_id' => 3,
                        'order_api_id' => $res_order_id
                    );
                    $repository->updateEntity($arrUpdate, $entity);

                    // update bank statement from trnf.tk
                    BankStatement::where('state_id', $data['stateid'])
                        ->update(['order_id' => $order_code, 'state_status' => '1', 'updated_at' => date('Y-m-d H:i:s')]);

                    $this->resJson['responseDetails']['requestApprove']['tran_id'] = $entity->id;

                }

                $this->resJson['responseDetails']['requestApprove']['approve_key'] = $approve_key;

            }

        }

        $this->resJson['responseStatus']['code'] = 200;
        $this->resJson['responseStatus']['message'] = "SUCCESS";
        $this->resJson['responseStatus']['messageDetails'] = "ทำรายการเรียบร้อย";

        $this->resJson['responseDetails']['aguser'] = $aguser;
        $this->resJson['responseDetails']['custid'] = $username;
        $this->resJson['responseDetails']['staffid'] = (isset($custom['admin_id'])) ? $custom['admin_id'] : null;
        $this->resJson['responseDetails']['staffname'] = (isset($custom['admin_name'])) ? $custom['admin_name'] : null;
        $this->resJson['responseDetails']['agentcredit'] = (float)$agcredit_bf;
        $this->resJson['responseDetails']['agentcredit_af'] = (float)$agcredit_af;
        $this->resJson['responseDetails']['banlance'] = (float)$balance;
        $this->resJson['responseDetails']['playing'] = (float)$playing;
        $this->resJson['responseDetails']['netbanlance'] = (float)$netbalance;
        $this->resJson['responseDetails']['transfer'] = ($data['type'] == "transfer") ? (float)$amount : 0;
        $this->resJson['responseDetails']['afterbanlance'] = (float)$afterbalance;
        $this->resJson['responseDetails']['order_api_id'] = $res_order_id;
        $this->resJson['responseDetails']['statuswd'] = $statuswd;
        $this->resJson['responseCustom'] = (!empty($custom)) ? $custom : null;

        if($data['type'] == "transfer") {

            $arrUpdateLog['response_time'] = date('Y-m-d H:i:s');
            $arrUpdateLog['response_data'] = json_encode($this->resJson, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_UNICODE);
            TransfersLog::where('id', $log->id)->update($arrUpdateLog);

        }

        return $this->returnJson();

    }

}