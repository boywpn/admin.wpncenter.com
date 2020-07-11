<?php

namespace Modules\Upc\Http\Controllers\V1;

use app\cryptor;
use App\Models\Trnf\BankStatement;
use App\Models\Trnf\TmpBankAuto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Api\Http\Controllers\Job\JobsApiController;
use Modules\Core\Games\Entities\Games;
use Modules\Core\Username\Entities\Username;
use Modules\Job\Jobs\Http\Controllers\JobsController;
use Modules\Member\Members\Entities\Members;
use Modules\Upc\Http\Controllers\UpcController;
use Modules\Wallet\Jobs\Entities\Jobs;
use Modules\Wallet\Jobs\Entities\JobsCallback;

/**
 *
 * Class ContactsApiController
 *
 * @package Modules\Api\Http\Controllers
 */
class WalletController extends UpcController
{
    public $ch;
    public $cookie_file;
    public $token_file;
    public $apiurl;
    public $header;

    public function __construct()
    {

        parent::__construct();

        $dir = dirname(__FILE__);
        $this->cookie_file = $dir . '/cookies/cookies.txt';
        $this->token_file = $dir . '/cookies/token.txt';
        $this->apiurl = 'http://wallet.wpncenter.com/api/';

    }

    public function curlPost($data_post, $action){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiurl.$action);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer '.$this->getToken(),
            'Content-Type: application/json',
            'Accept: application/json'
        ));
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.103 Safari/537.36');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_post));

        $data = curl_exec($ch);

        $json = json_decode($data, true);

        return $json;

    }

    public function getToken(){
        $token = "";
        if(file_exists($this->token_file)) {
            $token = file_get_contents($this->token_file);
        }
        return $token;
    }

    public function checkAuthen()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiurl.'user');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer '.$this->getToken(),
            'Content-Type: application/json',
            'Accept: application/json'
        ));
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.103 Safari/537.36');
        $data = curl_exec($ch);

        $json = json_decode($data, true);

        if($json['codeid'] != 0){
            $this->authen();
        }
    }

    public function authen(){
        $timeout= 120;
        $data_post = json_encode(['email' => 'token@wpnall.com', 'password' => 'aa112233']);
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $this->apiurl.'login');
        curl_setopt($this->ch, CURLOPT_FAILONERROR, true);
        curl_setopt($this->ch, CURLOPT_HEADER, 0);
        curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->cookie_file);
        curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->cookie_file);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($this->ch, CURLOPT_AUTOREFERER, true );
        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, $timeout );
        curl_setopt($this->ch, CURLOPT_TIMEOUT, $timeout );
        curl_setopt($this->ch, CURLOPT_MAXREDIRS, 10 );
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.103 Safari/537.36');
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json'
        ));
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data_post);

        $data = curl_exec($this->ch);

        $json = json_decode($data, true);

        if($json['codeid'] != 0){
            return $this->error($json['codeid']);
        }

        if(isset($json['data']['token'])) {
            file_put_contents($this->token_file, $json['data']['token']);
        }

    }

    public function balance(Request $request){

        $this->checkAuthen();

        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if($validator->fails()){
            return $this->error(103, $validator->errors());
        }

        $input = $request->input();

        $data_post = [
            'token' => $input['token']
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiurl.'v1/balance');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer '.$this->getToken(),
            'Content-Type: application/json',
            'Accept: application/json'
        ));
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.103 Safari/537.36');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_post));

        $data = curl_exec($ch);

        $json = json_decode($data, true);

        if($json['codeid'] != 0){
            return $this->error($json['codeid']);
        }

        return $this->success(0, ['balance' => $json['data']['balance']]);
    }

    public function transfer_old(Request $request){

        $validator = Validator::make($request->all(), [
            'method' => 'required|in:credit,debit',
            'type' => 'required',
            'token' => 'required',
            'orderId' => 'required',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ],[
            'method.in' => parent::CODEID[505],
            'amount.regex' => parent::CODEID[506]
        ]);

        if($validator->fails()){
            return $this->error(103, $validator->errors());
        }

        $input = $request->input();

        $tranfer = $this->doTransfer($input);
        if(!$tranfer['status']){
            return $this->error($tranfer['codeid']);
        }

        return $this->success($tranfer['codeid'], $tranfer['data']);

    }

    public function deposit(Request $request){

        $input = $request->input();

        $validator = Validator::make($input, [
            'type' => 'required',
            // 'tmp_id' => 'required',
            'state_id' => 'required',
            'state_date' => 'required',
            'member_id' => 'required',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ],[
            'amount.regex' => parent::CODEID[506]
        ]);

        if($validator->fails()){
            return $this->error(103, $validator->errors());
        }

        return $this->transferManual($input);
    }

    public function transferManual($input){

        $base = new \Modules\Member\Members\Http\Controllers\MembersController();

        $amount = $input['amount'];
        $tmp_id = (isset($input['tmp_id'])) ? $input['tmp_id'] : null; // If from tmp_bank auto system
        $statement_id = $input['state_id'];
        $statement_created = $input['state_date'];
        $member_id = $input['member_id'];
        $type = $input['type'];

        // Check amount match with statement
        if($type == 1){
            $tmp = BankStatement::where('state_id', $statement_id)
                ->where('state_deposit', $amount)
                ->first();

            if(!$tmp && $member_id!=48){
                return $this->error(701);
            }
        }

        if(in_array($type, [2,3])){
            return $this->error(702);
        }

        // $member = $base->saveMember($member_id); // Move from old system

        // Generate OneTime Key
        $onetime_key = rand12(16);
        // Update key to member
        $member = Members::where('id', $member_id)->update(['onetime_key' => $onetime_key]);

        // Generate Token Onetime
        \cryptor::setKey(env('CRYPTOR_SALT_WALLET'), env('CRYPTOR_KEY_WALLET'));
        $encrypt = \cryptor::encrypt($member_id."_".$onetime_key);
        $token_wallet = $encrypt;
        $token_wallet_en = \cryptor::decrypt($encrypt);

        // Set Data Wallet
        $data_post = [
            'method' => ($type == 1) ? 'credit' : 'debit',
            'type' => $type, // From Statement
            'token' => $token_wallet,
            'member_id' => $member_id,
            'state_id' => $statement_id,
            'created_at' => $statement_created,
            'amount' => $amount,
            'onetime_key' => $onetime_key,
        ];

        $entity = $this->saveWallet($data_post, $type);
        // Set entity of wallet to wallet api
        $data_post['wallet_id'] = $entity['id'];
        $data_post['orderId'] = $statement_id;

        // Auto Transfer Wallet
        $transfer = [];
        $transfer = $this->doTransfer($data_post);
        $json = json_encode($transfer, JSON_UNESCAPED_UNICODE);

        // Update Status to Success or Not
        // Update status wallet
        $repository = $this->getRepository(Jobs::class);
        $entity = $repository->findWithoutFail($entity['id']);

        $datetime = date('Y-m-d H:i:s');
        if($transfer['codeid'] == 0){
            // Update status wallet
            $repository->updateEntity(['transaction_id' => $transfer['data']['id'], 'status_id' => 3, 'response_at' => $datetime, 'response_result' => $json], $entity);

            // Update Status to Success
            if(!empty($tmp_id)) {
                TmpBankAuto::where('id', $tmp_id)->update(['status' => 2, 'success_at' => $datetime, 'response_transfer' => $json]);
            }
            // Update Statement Status
            BankStatement::where('state_id', $statement_id)->update(['tmp_id' => $tmp_id, 'wallet_used_at' => $datetime]);

            // Send noti to line group
            $textnoti = "\r\nรายการจาก Admin";
            $textnoti .= "\r\nรายการฝาก: ".$statement_id;
            $textnoti .= "\r\nจำนวน: ".$amount;
            $textnoti .= "\r\nMember ID: ".$member_id;
            $textnoti .= "\r\nสร้างเมื่อ: ".$datetime;
            $textnoti .= "\r\nเวลาธนาคาร: ".$statement_created;
            // line_notify($textnoti, '');

        }else{
            // Update Status to Error
            if(!empty($tmp_id)) {
                TmpBankAuto::where('id', $tmp_id)->update(['status' => 3, 'response_transfer_error' => $json]);
            }
            // Update status wallet
            $repository->updateEntity(['status_id' => 4, 'response_at' => $datetime, 'response_result' => $json], $entity);
        }

        $arrData = [
            'data_post' => $data_post,
            'amount' => $amount,
            'member' => $member,
            'tranfer' => $transfer,
            'token_wallet' => $token_wallet,
            'token_wallet_en' => $token_wallet_en
        ];

        return $arrData;

    }

    public function confirm(Request $request)
    {

        $input = $request->input();

        /**
         * Log Callback Confirmed Request
        */
        $repository = $this->getRepository(JobsCallback::class);
        $arrRequest = [
            'type' => 'confirmed',
            'request' => json_encode($input, JSON_UNESCAPED_UNICODE)
        ];
        $repository->createEntity($arrRequest, \App::make(JobsCallback::class));

        $validator = Validator::make($input, [
            'wallet_id' => 'required',
            'status_id' => 'required',
            'order_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->error(103, $validator->errors());
        }

        $wallet_id = $input['wallet_id'];
        $status_id = $input['status_id'];
        $order_id = $input['order_id'];
        $notes = $input['notes'];

        $jsonPost = json_encode($input, JSON_UNESCAPED_UNICODE);

        // Check Game ID is Enable to Use
        $job = Jobs::where('id', $wallet_id)
            ->where('status_id', 2)
            ->where('order_id', $order_id)
            ->first();
        if(empty($job)){
            return $this->errorMsg(603);
        }

        $job = $job->toArray(0);
        $member_id = $job['member_id'];

        // Generate OneTime Key
        $onetime_key = rand12(16);
        // Update key to member
        Members::where('id', $member_id)->update(['onetime_key' => $onetime_key]);
        // Generate Token Onetime
        \cryptor::setKey(env('CRYPTOR_SALT_WALLET'), env('CRYPTOR_KEY_WALLET'));
        $encrypt = \cryptor::encrypt($member_id."_".$onetime_key);
        $token_wallet = $encrypt;

        $repository = $this->getRepository(Jobs::class);
        $wallet= $repository->find($wallet_id);

        /**
         * If type_id = 2 then cancel = have to void credit to wallet
         * If type_id = 3 then
         *      cancel = do nothing
         *      confirmed = have to deduce wallet credit
         */

        /**
         * Void credit to wallet
         * If type_id = 2 then cancel = have to void credit to wallet
         */
        $void = [];
        if($job['type_id'] == 2 && $status_id == 5) {
            // Void to wallet
            $dataVoid = [
                'wallet' => [
                    'token' => $token_wallet,
                    'amount' => $job['amount'] * -1,
                    'member_id' => $member_id,
                    'void_from_id' => $job['id'],
                    'game_id' => 0,
                    'onetime_key' => $onetime_key,
                ],
                'transfer' => [
                    'method' => 'void',
                    'type' => 4, // From Void
                    'token' => $token_wallet,
                    'member_id' => $member_id,
                    'wallet_id' => null,
                    'orderId' => null,
                    'created_at' => null,
                    'amount' => $job['amount'] * -1
                ]
            ];
            $void = $this->voidWallet($dataVoid, 7);
        }

        /**
         * Add credit to wallet
         * If type_id = 3 then success = have to add credit to wallet
         */
        $transfer = [];
        $transaction_id = null;
        if($job['type_id'] == 3 && $status_id == 3) {
            $data_wallet = [
                'method' => 'credit',
                'type' => 2, // From Order
                'token' => $token_wallet,
                'member_id' => $member_id,
                'wallet_id' => $job['id'],
                'orderId' => $order_id,
                'created_at' => datetime(),
                'amount' => $job['amount']
            ];

            // Push data to Wallet
            $transfer = $this->doTransfer($data_wallet);
            $json = json_encode($transfer, JSON_UNESCAPED_UNICODE);

            // Update response transfer
            $repository->updateEntity(['response_at' => datetime(), 'response_result' => $json], $wallet);

            if ($transfer['codeid'] != 0) {
                // Update status wallet to error
                $repository->updateEntity(['status_id' => 7], $wallet);
            }

            $transaction_id = $transfer['data']['id'];
        }

        $response = $this->successMsg(0, compact('void', 'transfer'));
        $jsonRes = json_encode($response, JSON_UNESCAPED_UNICODE);

        $repository->updateEntity(['status_id' => $status_id, 'status_notes' => ((!empty($notes)) ? $notes : null), 'transaction_id' => $transaction_id, 'callback_at' => datetime(), 'callback_result' => $jsonPost, 'callback_response' => $jsonRes], $wallet);
        // $repository->updateEntity(['status_notes' => ((!empty($notes)) ? $notes : null), 'transaction_id' => $transaction_id, 'callback_at' => datetime(), 'callback_result' => $jsonPost, 'callback_response' => $jsonRes], $wallet);

        return $response;
    }

    public function transfer(Request $request){
        $input = $request->input();

        $validator = Validator::make($input, [
            'type' => 'required',
            'token' => 'required',
            'game_id' => 'required',
            'username_id' => 'required',
            'auto' => 'required',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ],[
            'amount.regex' => parent::CODEID[506]
        ]);

        if($validator->fails()){
            return $this->error(103, $validator->errors());
        }

        if($input['type'] == 8){
            $validator = Validator::make($input, [
                'to_game_id' => 'required',
                'to_username_id' => 'required'
            ]);
            if($validator->fails()){
                return $this->error(103, $validator->errors());
            }

            $this->response_array = true;
            $arrFrom = [
                'type' => 3,
                'token' => $input['token'],
                'game_id' => $input['game_id'],
                'username_id' => $input['username_id'],
                'auto' => $input['auto'],
                'amount' => $input['amount'],
            ];
            $from = $this->setTransfer($arrFrom);

            if(!$from['status']){
                $this->response_array = false;
                return $from;
            }

            $this->response_array = true;
            $to = [];
            if($from['status']){
                $ref_job_id = $from['data']['order']['job_id'];
                $arrTo = [
                    'type' => 2,
                    'ref_job_id' => $ref_job_id,
                    'token' => $input['token'],
                    'game_id' => $input['to_game_id'],
                    'username_id' => $input['to_username_id'],
                    'auto' => $input['auto'],
                    'amount' => $input['amount'],
                ];
                $to = $this->setTransfer($arrTo);

                if(!$to['status']){
                    $this->response_array = false;
                    return $to;
                }
            }

            $this->response_array = false;
            $data = compact('from', 'to');

            return $this->success(0, $data);

        }else{

            return $this->setTransfer($input);

        }
    }

    public function setTransfer($input){

        // Update status wallet
        $datetime = date('Y-m-d H:i:s');

        $wallet = new WalletController();

        // Check Game ID is Enable to Use
        $ck_game = Games::where('id', $input['game_id'])
            ->where('is_active', 1)
            ->count();
        if($ck_game == 0){
            return $this->error(507);
        }

        $token = $this->mathcKey($input['token'], 'key_change', 'front');
        if($token['codeid'] != 0){
            return $this->error(406);
        }

        $member_id = $token['data']['member_id'];
        $member = Members::findOrFail($member_id);

        $username = Username::where('id', $input['username_id'])->where('member_id', $member_id)->first();
        if(!$username){
            return $this->error(404);
        }

            // Generate OneTime Key
        $onetime_key = rand12(16);
        // Update key to member
        Members::where('id', $member_id)->update(['onetime_key' => $onetime_key]);

        // Generate Token Onetime
        \cryptor::setKey(env('CRYPTOR_SALT_WALLET'), env('CRYPTOR_KEY_WALLET'));
        $encrypt = \cryptor::encrypt($member_id."_".$onetime_key);
        $token_wallet = $encrypt;

        // Set Data Wallet
        $data_post = [
            'token' => $token_wallet,
            'amount' => $input['amount'],
            'ref_job_id' => (!empty($input['ref_job_id'])) ? $input['ref_job_id'] : null, // For type 7
            'member_id' => $member_id,
            'game_id' => $input['game_id'],
            'username_id' => $input['username_id'],
            'auto' => $input['auto'],
            'onetime_key' => $onetime_key,
            'company_id' => $member->company_id
        ];

        $entity = $wallet->saveWallet($data_post, $input['type']);

        /**
         * Reduce Wallet First
        */
        $repository = $this->getRepository(Jobs::class);
        $wallet = $repository->find($entity['id']);
        $repository->updateEntity(['status_id' => 2], $wallet); // Change Status to Inprogress

        // Action Wallet
        $data_wallet = [];
        $transfer = [];
        $transaction_id = null;

        if($input['type'] == 2) { // Withdraw Wallet to Casino

            $data_wallet = [
                'method' => 'debit',
                'type' => 2, // From Order
                'token' => $token_wallet,
                'member_id' => $member_id,
                'wallet_id' => $entity['id'],
                'orderId' => $entity['id'],
                'created_at' => datetime(),
                'amount' => $input['amount']
            ];

            // Push data to Wallet
            $transfer = $this->doTransfer($data_wallet);
            $json = json_encode($transfer, JSON_UNESCAPED_UNICODE);

            // Update response transfer
            $repository->updateEntity(['response_at' => datetime(), 'response_result' => $json], $wallet);

            if ($transfer['codeid'] != 0) {
                // Update status wallet to error
                $repository->updateEntity(['status_id' => 7], $wallet);
            }

            $transaction_id = $transfer['data']['id'];
        }
        /**
         * End Reduce Wallet
        */

        // Set entity of wallet to wallet api
        $data_post['wallet_id'] = $entity['id'];
        $data_post['created_at'] = $entity['created_at'];
        $data_post['orderId'] = $entity['ref'].$entity['id'];
        $data_post['method'] = ($input['amount'] > 0) ? "credit" : "debit";
        $data_post['type'] = 2; // From Order

        if($input['type'] == 3){ // Deposit to Wallet, Order have to decrease or -
            $data_post['amount'] = $input['amount'] * -1;
        }

        // return $data_post;

        // Created Order Log
        $job = new JobsController();
        $order = $job->createJob($data_post, $input['type']);
        $json = json_encode($order, JSON_UNESCAPED_UNICODE);

        // Update Job Status
        if($order['status']){
            $arrJobUpdate = [
                'status_id' => 3,
                'cust_credit_bf' => $order['data']['responseDetails']['banlance'],
                'cust_outstanding' => $order['data']['responseDetails']['playing'],
                'cust_credit_af' => $order['data']['responseDetails']['afterbanlance'],
                'bd_credit_bf' => $order['data']['responseDetails']['agentcredit'],
                'bd_credit_af' => $order['data']['responseDetails']['agentcredit_af'],
            ];
        }else{
            $arrJobUpdate = [
                'status_id' => 1
            ];
        }
        \Modules\Job\Jobs\Entities\Jobs::where('id', $order['job_id'])->update($arrJobUpdate);

        // Update status wallet
        $repository->updateEntity(['transaction_id' => $transaction_id, 'job_id' => $order['job_id'], 'response_order_at' => datetime(), 'response_order_result' => $json], $wallet);

        /**
         * Have to create void
        */
        if($order['codeid'] == 601){

            // Update status wallet
            $repository->updateEntity(['status_id' => 7], $wallet);

            /**
             * Credit will be void when admin system have some issue.
            */
            $void = [];
            if($input['type'] == 2) { // Wallet to Game Only
                // Void to wallet
                $dataVoid = [
                    'wallet' => [
                        'token' => $token_wallet,
                        'amount' => $input['amount'],
                        'member_id' => $member_id,
                        'void_from_id' => $entity['id'],
                        'game_id' => 0,
                        'onetime_key' => $onetime_key,
                    ],
                    'transfer' => [
                        'method' => 'void',
                        'type' => 4, // From Void
                        'token' => $token_wallet,
                        'member_id' => $member_id,
                        'wallet_id' => null,
                        'orderId' => null,
                        'created_at' => null,
                        'amount' => $input['amount']
                    ]
                ];
                $void = $this->voidWallet($dataVoid, 6);
            }

            return $this->error(601, ['msg' => parent::CODEID[601].$order['msg'], 'ref' => $entity['ref'], 'amount' => $entity['amount'], 'created_at' => $entity['created_at'], 'order' => $order, 'data_wallet' => $data_wallet, 'transfer' => $transfer, 'void' => $void], parent::CODEID[601].$order['msg']);
        }

        /**
         * Have to check by admin
         */
        if($order['codeid'] == 602){
            // return $this->success(0, ['code' => 602, 'msg' => parent::CODEID[602], 'ref' => $entity['ref'], 'amount' => $entity['amount'], 'created_at' => $entity['created_at'], 'order' => $order, 'data_wallet' => $data_wallet, 'transfer' => $transfer]);
            return $this->error(602);
        }

        /**
         * Transfer to wallet if auto credit success for type 3 CASINO TO WALLET
        */
        if($input['type'] == 3){ // Deposit Casino to Wallet

            $data_wallet = [
                'method' => 'credit',
                'type' => 2, // From Order
                'token' => $token_wallet,
                'member_id' => $member_id,
                'wallet_id' => $entity['id'],
                'orderId' => $entity['id'],
                'created_at' => datetime(),
                'amount' => $input['amount']
            ];

            // Push data to Wallet
            $transfer = $this->doTransfer($data_wallet);
            $json = json_encode($transfer, JSON_UNESCAPED_UNICODE);

            // Update response transfer
            $repository->updateEntity(['response_at' => datetime(), 'response_result' => $json], $wallet);

            if ($transfer['codeid'] != 0) {
                // Update status wallet to error
                $repository->updateEntity(['status_id' => 7], $wallet);
            }

            $transaction_id = $transfer['data']['id'];
        }

        /**
         * Success
        */
        // Update status wallet
        $repository->updateEntity(['transaction_id' => $transaction_id, 'job_id' => $order['job_id'], 'status_id' => 3], $wallet);

        //$code = 0;
        return $this->success(0, compact('order', 'transfer'));

    }

    public function transferIn($data){



    }

    public function voidWallet($data, $type = 6){

        $wallet = $data['wallet'];

        // Set Data Wallet
        $data_post = [
            'token' => $wallet['token'],
            'amount' => $wallet['amount'],
            'member_id' => $wallet['member_id'],
            'void_from_id' => $wallet['void_from_id'],
            'game_id' => 0,
            'onetime_key' => $wallet['onetime_key'],
        ];

        $entity = $this->saveWallet($data_post, $type); // 6 id void to wallet

        $repository = $this->getRepository(Jobs::class);
        $wallet= $repository->find($entity['id']);
        $repository->updateEntity(['status_id' => 2], $wallet); // Change Status to Inprogress

        $transfer = $data['transfer'];
        $transfer['wallet_id'] = $entity['id'];
        $transfer['orderId'] = $entity['id'];
        $transfer['created_at'] = datetime();
        // Push data to Wallet
        $transfer = $this->doTransfer($transfer);
        $json = json_encode($transfer, JSON_UNESCAPED_UNICODE);

        // Update response transfer
        $repository->updateEntity(['response_at' => datetime(), 'response_result' => $json], $wallet);

        if($transfer['codeid'] != 0){
            // Update status wallet to error
            $repository->updateEntity(['status_id' => 7], $wallet);
        }else{
            // Update status wallet to error
            $repository->updateEntity(['status_id' => 3], $wallet);
        }

        return compact('entity', 'transfer');
    }

    public function createOrder($data){

        $data = [
            'member_id' => $data['member_id'],
            'cash' => $data['amount'],
            'game_id' => $data['game_id'],
            'datetime' => $data['created_at'],
            'stateid' => 222222, // From Wallet to Casino
            'wallet_id' => $data['wallet_id'],
            'auto' => $data['auto'],
        ];

        $url = 'https://admin.wpnadmin.com/v1/auto_transaction/';
        $username = $this->getCURL($url, 'POST', $data);

        $json = json_decode($username, true);

        return $json;

    }

    public function doTransfer($input){

        $this->checkAuthen();

        $data_post = [
            'method' => $input['method'],
            'type' => $input['type'],
            'token' => $input['token'],
            'wallet_id' => $input['wallet_id'],
            'orderId' => $input['orderId'],
            'created_at' => $input['created_at'],
            'amount' => $input['amount'],
        ];

        \cryptor::setKey(env('CRYPTOR_SALT_WALLET'), env('CRYPTOR_KEY_WALLET'));
        $key = \cryptor::decrypt($input['token']);
        $ekey = explode("_", $key);
        if(!isset($ekey[1])){
            return $this->errorMsg(406);
        }
        $member_id = $ekey[0];
        $onetime_key = $ekey[1];

        // check onetime key
        $ck_onetime = Members::where('id', $member_id)
            ->where('onetime_key', $onetime_key)
            ->first();
        if(!$ck_onetime){
            return $this->errorMsg(406);
        }

        // Update status wallet
        $repository = $this->getRepository(Jobs::class);
        $entity = $repository->findWithoutFail($input['wallet_id']);
        $repository->updateEntity(['status_id' => 2], $entity);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiurl.'v1/transfer');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer '.$this->getToken(),
            'Content-Type: application/json',
            'Accept: application/json'
        ));
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.103 Safari/537.36');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_post));

        $data = curl_exec($ch);

        $json = json_decode($data, true);

        if($json['codeid'] != 0){
            return $this->errorMsg($json['codeid'], $json);
        }

        return $this->successMsg(0, [
            'amount' => $json['data']['amount'],
            'balance' => $json['data']['balance'],
            'tnxID' => $json['data']['tnxID'],
            'id' => $json['data']['id'],
        ]);
    }

    public function histories_callWallet(Request $request){

        $input = $request->input();

        $last_seven = date('Y-m-d', strtotime('-7 days', strtotime(date('Y-m-d'))));

        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'type' => 'required', // 1=statement, 2=order, 3=event
            'startDate' => 'date_format:Y-m-d|after_or_equal:'.$last_seven,
            'endDate' => 'date_format:Y-m-d|after_or_equal:startDate',
        ]);

        if($validator->fails()){
            return $this->error(403, $validator->errors());
        }

        $curl = $this->curlPost($input, 'v1/histories');

        return $curl;

    }

    public function histories(Request $request){

        $input = $request->input();

        $last_seven = date('Y-m-d', strtotime('-7 days', strtotime(date('Y-m-d'))));

        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'group' => 'required', // 1=statement, 2=order, 3=event
            'startDate' => 'date_format:Y-m-d|after_or_equal:'.$last_seven,
            'endDate' => 'date_format:Y-m-d|after_or_equal:startDate',
        ]);

        if($validator->fails()){
            return $this->error(403, $validator->errors());
        }

        $token = $this->mathcKey($input['token'], 'key_change', 'front');
        if($token['codeid'] != 0){
            return $this->errorMsg(406);
        }

        $transactions = Jobs::where('member_id', $token['data']['member_id'])
            ->whereIn('group_id', $input['group'])
            ->whereNotIn('status_id', [6,7])
            ->whereRaw('DATE(created_at) >= ? AND DATE(created_at) <= ?', [$input['startDate'], $input['endDate']])
            ->with(['jobsStatus' => function($query){
                $query->select('id', 'name');
            }])
            ->with(['jobsGame' => function($query){
                $query->select('id', 'name');
            }])
            ->with(['jobsVoid' => function($query){
                $query->select('id', 'ref');
            }])
            ->orderby('id', 'desc')
            ->get()
            ->toArray();

        $arrData = [];
        $arrRow = [];

        $arrData['id'] = $token['data']['member_id'];
        $arrData['records'] = count($transactions);

        foreach ($transactions as $transaction){

            $arrRow[] = [
                'id' => $transaction['id'],
                'status_id' => $transaction['jobs_status']['id'],
                'status' => $transaction['jobs_status']['name'],
                'ref' => $transaction['ref'],
                'method' => parent::WALLET_MATHOD[$transaction['type_id']],
                'type_id' => $transaction['type_id'],
                'game_id' => $transaction['game_id'],
                'game_name' => $transaction['jobs_game']['name'],
                'void_ref' => $transaction['jobs_void']['ref'],
                'amount' => $transaction['amount'],
                'created_at' => $transaction['created_at'],
            ];
        }

        $arrData['list'] = $arrRow;

        return $this->success(0, $arrData);

    }

    public function saveWallet($data, $type){

        /**
         *  Type
         *  1. From Statement
         *  2. To Order
         */

        $repository = $this->getRepository(Jobs::class);

        $input = [
            'member_id' => $data['member_id'],
            'onetime_key' => $data['onetime_key'],
            'amount' => $data['amount'],
            'type_id' => $type,
            'status_id' => 1
        ];

        if($type == 1){
            $input['ref'] = 'IN'.date('YmdHis');
            $input['state_id'] = $data['state_id'];
            $input['group_id'] = 1;
        }
        elseif($type == 2){ // Wallet to Casino -
            $input = [
                'member_id' => $data['member_id'],
                'ref' => 'OUT'.date('YmdHis'),
                'onetime_key' => $data['onetime_key'],
                'game_id' => $data['game_id'],
                'username_id' => $data['username_id'],
                'group_id' => 2,
                'amount' => $data['amount'] * -1,
                'type_id' => $type,
                'status_id' => 1
            ];
        }
        elseif($type == 3){ // Casino to Wallet +
            $input = [
                'member_id' => $data['member_id'],
                'ref' => 'IN'.date('YmdHis'),
                'onetime_key' => $data['onetime_key'],
                'game_id' => $data['game_id'],
                'username_id' => $data['username_id'],
                'group_id' => 2,
                'amount' => $data['amount'],
                'type_id' => $type,
                'status_id' => 1
            ];
        }
        elseif($type == 4){ // Wallet to Statement
            $input['ref'] = 'OUT'.date('YmdHis');
            $input['state_id'] = $data['state_id'];
            $input['group_id'] = 1;
            $input['amount'] = $data['amount'] * -1;

        }
        elseif($type == 5){ // Event to Wallet
            $input['ref'] = 'IN'.date('YmdHis');
            $input['state_id'] = $data['state_id'];
            $input['group_id'] = 3;
        }
        elseif($type == 6){ // Void to Wallet
            $input['ref'] = 'VOID'.date('YmdHis');
            $input['void_from_id'] = $data['void_from_id'];
            $input['group_id'] = 4;
        }
        elseif($type == 7){ // Void from cancel
            $input['ref'] = 'VOID'.date('YmdHis');
            $input['void_from_id'] = $data['void_from_id'];
            $input['group_id'] = 2;
        }


        $entity = $repository->createEntity($input, \App::make(Jobs::class));

        return $entity->toArray();

    }

    public function callback($id, $response){



    }

}