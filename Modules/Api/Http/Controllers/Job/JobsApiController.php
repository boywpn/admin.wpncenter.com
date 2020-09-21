<?php

namespace Modules\Api\Http\Controllers\Job;


use App\Models\Old\OrdersNew;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Api\Http\Controllers\Base\CrudApiController;
use Modules\Api\Http\Controllers\Game\TransferApiController;
use Modules\Core\Banks\Entities\Banks;
use Modules\Core\BanksPartners\Entities\BanksPartners;
use Modules\Core\Promotions\Entities\Promotions;
use Modules\Job\Jobs\Entities\Jobs;
use Modules\Job\Jobs\Http\Requests\JobsRequest;
use Modules\Member\Members\Entities\Members;
use Modules\Member\Members\Entities\MembersBanks;
use Modules\Member\Members\Http\Controllers\MembersController;
use Modules\Member\Members\Http\Requests\MembersRequest;
use Modules\Platform\Core\Traits\APITrait;

/**
 *
 * Class ContactsApiController
 *
 * @package Modules\Api\Http\Controllers
 */
class JobsApiController extends CrudApiController
{

    protected $entityClass = Jobs::class;

    protected $moduleName = 'jobJobs';

    protected $languageFile = 'job/jobs::jobs';

    protected $resJson;

    protected $with = [
        'jobsMember',
        'jobsUsername',
        'jobsStatus',
        'jobsType',
    ];

    protected $permissions = [
        'browse' => 'job.jobs.browse',
        'create' => 'job.jobs.create',
        'update' => 'job.jobs.update',
        'destroy' => 'job.jobs.destroy'
    ];

    protected $showRoute = 'job.jobs.show';

    protected $storeRequest = JobsRequest::class;

    protected $updateRequest = JobsRequest::class;

    public function __construct()
    {
        parent::__construct();

        $this->resJson['accessTokenResponse']['responseStatus']['code'] = 100;
        $this->resJson['accessTokenResponse']['responseStatus']['message'] = "SUCCESS";
        $this->resJson['accessTokenResponse']['responseStatus']['messageDetails'] = 'เชื่อมต่อ API ได้สำเร็จ';
        $this->resJson['accessTokenResponse']['date'] = date('Y-m-d H:i:s');

    }

    public function setTransfer($request)
    {

        $api = new TransferApiController();
        return $api->transfer($request);

    }

    public function genJob(Request $request)
    {

        if ($this->permissions['create'] != '' && !\Auth::user()->hasPermissionTo($this->permissions['create'])) {
            $this->resJson['responseStatus']['code'] = 201;
            $this->resJson['responseStatus']['message'] = "ERROR";
            $this->resJson['responseStatus']['messageDetails'] = 'ไม่มีสิทธิ์ในการเข้าถึงข้อมูลส่วนนี้';
            return $this->resJson;
        }

        $data = $request->all();

        if(!isset($data['orderid']) || $data['orderid'] == ""){

            $this->resJson['responseStatus']['code'] = 201;
            $this->resJson['responseStatus']['message'] = "ERROR";
            $this->resJson['responseStatus']['messageDetails'] = 'กรุณาใส่ข้อมูล Order ID';
            return false;

        }

        // check transfer _wd _dp
        $exp_order = explode("_", $data['orderid']);

        // Set new OrderID
        $orderid = $exp_order[0]."_".$exp_order[1];


        $json = file_get_contents('https://api.wpnadmin.com/core_api.php?ss=true&backend=true&action=order_detail_wpnservice2&id=' . $orderid);
        $json = json_decode($json, true);

//        // create curl resource
//        $ch = curl_init();
//        // set url
//        curl_setopt($ch, CURLOPT_URL, 'https://admin.wpnadmin.com/core_api.php?ss=true&backend=true&action=order_detail_wpnservice2&id=' . $orderid);
//        //return the transfer as a string
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        // $output contains the output string
//        return $output = curl_exec($ch);
//        // close curl resource to free up system resources
//        curl_close($ch);
//
//        $json = json_decode($output, true);

        if (!$json['status']) {
            $this->resJson['responseStatus']['code'] = 201;
            $this->resJson['responseStatus']['message'] = "ERROR";
            $this->resJson['responseStatus']['data'] = $json;
            $this->resJson['responseStatus']['messageDetails'] = 'ไม่มีรายการที่ต้องการ';
            return $this->resJson;
        }

        return $this->createJob($request, $json);
    }

    /**
     * Store entity
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     *
     * This function request from admin.wpnadmin.com
     *
     */
    public function createJob($request, $json = null)
    {

        $data = $request->all();

//        print_r($data);
//        exit;

//        print_r($json);
//        exit;

        $orderid = $data['orderid'];
        $member = $json['packet']['member'];
        $order = $json['packet']['order'];
        $to = (isset($json['packet']['web_to'])) ? $json['packet']['web_to'] : false; // only deposit ans transfer
        $from = $json['packet']['web_from'];
        $bank_system = $json['packet']['bank_system'];

        // Check if from wallet then if have will change to wallet identify
        if(!empty($order['wallet_id']) && $order['finance_type'] == 1){
            $order['finance_type'] = 4; // Check type to wallet -> casino
        }
        if(!empty($order['wallet_id']) && $order['finance_type'] == 2){
            $order['finance_type'] = 5; // Check type to casino -> wallet
        }

        $finance_type = $order['finance_type'];

        // check finance_type is 3 or transfer
        if($finance_type == 3){
            $exp_order = explode("_", $data['orderid']);
            if(end($exp_order) == "dp"){
                $finance_type = 1;
            }elseif(end($exp_order) == "wd"){
                $finance_type = 2;
            }else{
                $this->resJson['responseStatus']['code'] = 201;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = 'รายการอ้างอิงไม่ตรงกับประเภท ฝาก/ถอน';
                return $this->resJson;
            }
        }

        // check job exist
        $job = Jobs::where('order_code', $orderid)
            ->where('transfer_type', $finance_type)
            ->first();

        if($job){
            // If set auto transfer is true
            if($data['auto']){
                return $this->setTransfer($request);
            }

            $this->resJson['responseStatus']['code'] = 201;
            $this->resJson['responseStatus']['message'] = "ERROR";
            $this->resJson['responseStatus']['messageDetails'] = 'รายการนี้มีในระบบแล้ว';
            return $this->resJson;
        }

        $new_member = Members::getMember($member['new_id']);

        // find bank id
        $member_bank = MembersBanks::where('bank_number', $member['withdraw_ac'])->first();
        if(empty($member_bank)){
            $this->resJson['responseStatus']['code'] = 201;
            $this->resJson['responseStatus']['message'] = "ERROR";
            $this->resJson['responseStatus']['messageDetails'] = 'กรุณาเพิ่ม บัญชีธนาคารลูกค้า ในระบบใหม่';
            return $this->resJson;
        }

        if($order['finance_type'] == 1) {
            $core_bank = Banks::where('number', $bank_system['account_number'])->first();
            if (empty($core_bank)) {
                $this->resJson['responseStatus']['code'] = 201;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = 'กรุณาเพิ่ม บัญชีรับเงิน ในระบบใหม่';
                return $this->resJson;
            }

            $partner_bank = BanksPartners::where('bank_id', $core_bank->id)
                ->where('partner_id', $new_member['members_agent']['partner_id'])
                ->first();
            if (empty($partner_bank)) {
                $this->resJson['responseStatus']['code'] = 201;
                $this->resJson['responseStatus']['message'] = "ERROR";
                $this->resJson['responseStatus']['messageDetails'] = 'กรุณาเพิ่ม บัญชีเว็บ ในระบบใหม่';
                return $this->resJson;
            }
        }

        $input = [];
        $input['order_id'] = $order['A_I'];
        $input['order_code'] = $orderid;
        $input['code'] = time();
        $input['type_id'] = $order['finance_type']; // user original finance_type b/c need to use type of transfer for new system.
        $input['status_id'] = 2;
        $input['member_id'] = $member['new_id'];

        if($finance_type == 1){
            $input['username_id'] = $to['new_id'];
            $input['topup_from_bank'] = ($order['finance_type'] == 1) ? $member_bank->id : null;
            $input['topup_to_bank'] = ($order['finance_type'] == 1) ? $partner_bank->id : null;
            $input['topup_pay_at'] = ($order['finance_type'] == 1) ? $order['datetime'] : null;
        }elseif($finance_type == 2){
            $input['username_id'] = $from['new_id'];
            $input['withdraw_to_bank'] = $member_bank->id;
            $input['withdraw_at'] = $order['datetime'];
        }elseif($finance_type == 4){
            $input['username_id'] = $to['new_id'];
            $input['topup_from_bank'] = null;
            $input['topup_to_bank'] = null;
            $input['topup_pay_at'] = null;
            $input['wallet_id'] = $order['wallet_id'];
        }elseif($finance_type == 5){
            $input['username_id'] = $from['new_id'];
            $input['withdraw_to_bank'] = null;
            $input['withdraw_at'] = null;
            $input['wallet_id'] = $order['wallet_id'];
        }

        $input['transfer_type'] = $finance_type; // user for identify type of transaction.

        $input['amount'] = $order['money'];
        $input['promotion_amount'] = $order['bonus'];
        $input['total_amount'] = $order['amount'];

        $input['ip_address'] = $order['ipaddress'];
        $input['locked_at'] = ($order['lock2dt'] == "0000-00-00 00:00:00") ? "" : $order['lock2dt'];
        $input['locked_by_name'] = $order['lock2name'];

        $input['company_id'] = \Auth::user()->company_id;

        $request = \App::make(Request::class);

        $repository = $this->getRepository();

        $before = $this->beforeStoreInput($request, $input);

        if(!empty($before)){
            // return $this->respond(false, [], ['error' => 'input_invalid'], ['message' => $before['msg']]);
            $this->resJson['responseStatus']['code'] = 201;
            $this->resJson['responseStatus']['message'] = "ERROR";
            $this->resJson['responseStatus']['messageDetails'] = $before['msg'];
            return $this->resJson;
        }

        $entity = $repository->createEntity($input, \App::make($this->entityClass));

        $entity = $this->setupAssignedTo($entity, $request, true);
        $entity->save();

        // save match order to admin.wpnadmin.com
        $repository->createEntity(array('order_id' => $order['A_I'], 'order_code' => $orderid, 'order_new_id' => $entity->id), \App::make(OrdersNew::class));

        $this->afterStore($request, $entity);

        // If set auto transfer is true
        if($data['auto']){
            return $this->setTransfer($request);
        }

        $this->resJson['responseStatus']['code'] = 201;
        $this->resJson['responseStatus']['message'] = "SUCCESS";
        $this->resJson['responseStatus']['messageDetails'] = 'สร้างรายการใหม่เรียบร้อย';
        return $this->resJson;

    }

    public function beforeStoreInput($request, &$input)
    {
        $countOrder = Jobs::where('member_id', $input['member_id'])
            ->where('status_id', 3)
            ->count();

        $input['code'] = $input['code'] . '_' . $countOrder;
    }

    /**
     * After entity store
     * @param $request
     * @param $entity
     * @return bool
     */
    public function afterStore($request, &$entity)
    {



    }

}