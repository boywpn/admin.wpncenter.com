<?php

namespace Modules\Api\Http\Controllers\Game;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Modules\Api\Http\Controllers\Base\CrudApiController;
use Modules\Api\Traits\TransfersLogTrait;
use Modules\Job\Jobs\Entities\Jobs;
use Modules\Member\Members\Entities\Members;
use Modules\Member\Members\Http\Controllers\MembersController;
use Modules\Member\Members\Http\Requests\MembersRequest;
use Modules\Platform\Core\Repositories\GenericRepository;

/**
 *
 * Class ContactsApiController
 *
 * @package Modules\Api\Http\Controllers
 */
class GameApiController extends CrudApiController
{

    use TransfersLogTrait;

    protected $checkValidate = false;
    protected $resJson;

    const ACCEPT_ORDER_CODE = [
        '168168_lt', // From Lotto
        '168168_fb', // From Facebook
        '168168_mn', // From Manual
        '168168_comm', // From Manual
        'dev_test', // From Developer
        'fix_comm', // From Fix Commision
        'comm_auto', // From Auto Commission
        'loss_auto', // From Auto Loss Back
        'event_auto', // From Auto Event
        '168168_bn', // From Auto Loss Back
        'demo200', // Free Demo 200 Credit
        'agent_transfer', // For Agent Transfer
        'new_system' // For New System
    ];

    const ACCEPT_MIN_AMOUNT = [
        'dev_test', // From Developer
        '168168_comm', // From Manual
        'comm_auto', // From Auto Commission
        '168168_bn', // From Auto Commission
        'loss_auto', // From Auto Loss Back
        'event_auto', // From Auto Event
        'agent_transfer' // For Agent Transfer
    ];

    const PROMO_CHECK = [
        'demo200'
    ];
    const PROMO_ALLOW_CHECK_FROM = [
        'admin_cancel_events',
        'api_agent_transfer'
    ];

    const PROMO_CHECK_VAL = [
        'demo200' => [
            'setzero' => 1,
            'amount' => 200
        ]
    ];

    public function __construct()
    {
        // For Transfer Log
        $this->arrLog['request_time'] = date('Y-m-d H:i:s');

        // For Return Data
        $this->resJson['accessTokenResponse']['responseStatus']['code'] = 100;
        $this->resJson['accessTokenResponse']['responseStatus']['message'] = "SUCCESS";
        $this->resJson['accessTokenResponse']['responseStatus']['messageDetails'] = 'เชื่อมต่อ API ได้สำเร็จ';
        $this->resJson['accessTokenResponse']['date'] = date('Y-m-d H:i:s');

    }

    public function returnJson(){

        // return json_encode($this->resJson, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        return $this->resJson;

    }

    public function checkOrderRequest($request){

        // $request = \App::make(Request::class);

        $order = (isset($request['orderid'])) ? $request['orderid'] : null;
        $type = (isset($request['type'])) ? $request['type'] : null;
        $stateid = (isset($request['stateid'])) ? $request['stateid'] : null;

        if(empty($order) || empty($type)){

            if(empty($order) && empty($type)){

                $this->resJson['responseStatus']['code'] = 201;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = 'กรุณาใส่ข้อมูล Order ID และ Type';
                return false;

            }
            elseif(empty($order) && !empty($type)){

                $this->resJson['responseStatus']['code'] = 201;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = 'กรุณาใส่ข้อมูล Order ID';
                return false;

            }
            elseif(!empty($order) && empty($type)){

                $this->resJson['responseStatus']['code'] = 201;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = 'กรุณาใส่ข้อมูล Type';
                return false;

            }

        }
        else{

            if(!in_array($type, array('detail','transfer','approve'))){
                $this->resJson['responseStatus']['code'] = 201;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = 'กรุณาระบุ Type ให้ถูกต้อง';
                return false;
            }

        }

        if (empty($stateid)) {
            $this->resJson['responseStatus']['code'] = 201;
            $this->resJson['responseStatus']['message'] = "ERROR";
            $this->resJson['responseStatus']['messageDetails'] = 'กรุณาใส่ รายการอ้างอิงธนาคาร';
            return false;
        }

        return true;
    }

    public function checkRequest($request){

        // $request = \App::make(Request::class);

        $order = (isset($request['orderid'])) ? $request['orderid'] : null;
        $type = (isset($request['type'])) ? $request['type'] : null;
        $amount = (isset($request['amount'])) ? $request['amount'] : null;
        $custid = (isset($request['custid'])) ? $request['custid'] : null;
        $staffid = (isset($request['staffid'])) ? $request['staffid'] : null;
        $from = (isset($request['from'])) ? $request['from'] : null;
        $stateid = (isset($request['stateid'])) ? $request['stateid'] : null;
        $job_id = (isset($request['job_id'])) ? $request['job_id'] : null;
        $wallet_id = (isset($request['wallet_id'])) ? $request['wallet_id'] : null;

        if(empty($type)){

            $this->resJson['responseStatus']['code'] = 201;
            $this->resJson['responseStatus']['message'] = "ERROR";
            $this->resJson['responseStatus']['messageDetails'] = 'กรุณาใส่ข้อมูล Type';
            return false;

        }else{

            if(!in_array($type, array('detail','transfer','approve'))){
                $this->resJson['responseStatus']['code'] = 201;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = 'กรุณาระบุ Type ให้ถูกต้อง';
                return false;
            }

        }


        if(empty($custid)){

            $this->resJson['responseStatus']['code'] = 201;
            $this->resJson['responseStatus']['message'] = "ERROR";
            $this->resJson['responseStatus']['messageDetails'] = 'กรุณาใส่ Username ที่ต้องการ';
            return false;
        }
        if (empty($amount) && $amount != 0) {

            $this->resJson['responseStatus']['code'] = 201;
            $this->resJson['responseStatus']['message'] = "ERROR";
            $this->resJson['responseStatus']['messageDetails'] = 'กรุณาใส่ จำนวนเงิน ที่ต้องการ';
            return false;
        }

        if($type == 'transfer') {
            if (empty($order)) {

                $this->resJson['responseStatus']['code'] = 201;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = 'กรุณาใส่ข้อมูล Order ID';
                return false;
            }
            if (empty($staffid)) {

                $this->resJson['responseStatus']['code'] = 201;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = 'กรุณาใส่ Staff ที่ต้องการ';
                return false;
            }
            if (empty($from)) {

                $this->resJson['responseStatus']['code'] = 201;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = 'กรุณาใส่ From ที่ต้องการ';
                return false;
            }

            if(empty($job_id)) {
                if (empty($stateid)) {
                    $this->resJson['responseStatus']['code'] = 201;
                    $this->resJson['responseStatus']['message'] = "ERROR";
                    $this->resJson['responseStatus']['messageDetails'] = 'กรุณาใส่ รายการอ้างอิงธนาคาร';
                    return false;
                }
            }

        }

        return true;
    }

    public function getJobByOrder($order, $type = null)
    {

        $job = Jobs::where('order_code', $order)
            // ->where('type_id', $type)
            ->where('status_id', 2)
            ->with(['jobsUsername' => function ($query) {
                $query->select('*');
            }, 'jobsUsername.usernameBoard' => function ($query) {
                $query->select('*');
            }, 'jobsUsername.usernameBoard.boardsGame' => function ($query) {
                $query->select('*');
            }])
            ->first();

        return $job;
    }

    public function getJob($order, $type = null){

        $job = $this->getJobByOrder($order, $type = null);

        if(!$job){
            $this->resJson['responseStatus']['code'] = 201;
            $this->resJson['responseStatus']['message'] = "ERROR";
            $this->resJson['responseStatus']['messageDetails'] = 'ไม่พบข้อมูลรายการอ้างอิง '.$order.' หรือทำรายการเรียบร้อยแล้ว หรือสร้างรายการจากหน้าสำรอง';
            return false;
        }

        if(!isset($job['jobs_username']['username_board']['boards_game']['code'])){
            Artisan::call('cache:clear');

            $job = $this->getJobByOrder($order, $type = null);
        }

        $job = $job->toArray();

//        print_r($job);

        $return = [
            'id' => $job['id'],
            'type_id' => $job['type_id'],
            'transfer_type' => $job['transfer_type'],
            'username' => $job['jobs_username']['username'],
            'password' => Crypt::decryptString($job['jobs_username']['password']),
            'username_id' => $job['jobs_username']['id'],
            'game' => strtolower($job['jobs_username']['username_board']['boards_game']['code']),
            'game_id' => strtolower($job['jobs_username']['username_board']['boards_game']['id']),
            'key' => json_decode($job['jobs_username']['username_board']['api_code'], true),
            'total_amount' => $job['total_amount'],
            'code' => $job['code'],
            'order_code' => $job['order_code'],
            'type' => $job['type_id'],
            'aguser' => $job['jobs_username']['username_board']['member_prefix']
        ];

        return $return;

    }

}