<?php

namespace Modules\Upc\Http\Controllers\V1;

use app\cryptor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Modules\Core\Agents\Entities\Agents;
use Modules\Core\BanksPartners\Entities\BanksPartners;
use Modules\Core\Games\Entities\Games;
use Modules\Core\Partners\Entities\Partners;
use Modules\Core\Username\Entities\Username;
use Modules\Job\Jobs\Entities\Jobs;
use Modules\Job\Jobs\Http\Controllers\JobsController;
use Modules\Member\Members\Entities\Members;
use Modules\Member\Members\Entities\MembersBanks;
use Modules\Upc\Http\Controllers\UpcController;

/**
 *
 * Class ContactsApiController
 *
 * @package Modules\Api\Http\Controllers
 */
class MembersController extends UpcController
{

    protected $entityClass = Members::class;

    public function login(Request $request)
    {

        $input = $request->all();
        $data = [];
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
            'pid' => 'required',
        ]);
        if($validator->fails()){
            return $this->error(103, $validator->errors());
        }

        $partner = Partners::findOrFail($input['pid']);

        $on_newsystem = 0;
        $ck_new = 0;
        // Check exist in new system
        $member = \App\Models\Old\Members::where('username', $input['username'])
            ->where('password', $input['password'])
            ->where('domain', $partner->old_id)
            ->select('A_I','new_id','id','username','status','on_newsystem','on_newsystem_at')
            ->first();

        if(!$member){
            $ck_new = 1;
        }

        // Gen key_change when login
        $login_key = rand12(12);
        $data['secret_key'] = $login_key;

        if($ck_new == 0){
            $on_newsystem = $member['on_newsystem'];
        }

        // Check on new sytem
        if($ck_new == 0 && $on_newsystem == 0){

            $member = $member->toArray();

            // Check exist already some people another system before this system online.
            $member_new = Members::where('old_id', $member['A_I'])
                ->select('id','name','username','old_id','first_update','first_update_at')
                ->first();

            // If exist generate key_change for update username and password for UPC system.
            if($member_new){

                // Update key to member
                Members::where('id', $member_new->id)->update(['key_change' => $login_key]);

                $data['member'] = $member_new->toArray();
                $member_id = $member_new->id;

                // Check first update
                if(empty($member_new->first_update)){
                    $data['first_update'] = false;
                }else{
                    $data['first_update'] = true;
                }

            }
            // If have will be created
            else{

                $data['first_update'] = false;
                $cMember = new \Modules\Member\Members\Http\Controllers\MembersController();

                $member_new = $cMember->addMember($member['A_I']);
                $member_new = json_decode($member_new, true);
                $member_id = $member_new['data']['id'];

                // Update key to member
                Members::where('id', $member_id)->update(['key_change' => $login_key]);

            }

        }

        // If have on new system have to use new username
        else{

            $member = Members::where('username', $input['username'])
                ->select('id','name','username','password','old_id','first_update','first_update_at')
                ->first();

            if(!$member){
                return $this->error(102);
            }

            if (!Hash::check($input['password'], $member->password)) {
                return $this->error(102);
            }

            $member = $member->toArray();
            unset($member['password']);

            $member_id = $member['id'];
            // Update key to member
            Members::where('id', $member_id)->update(['key_change' => $login_key]);

            $data['member'] = $member;

            $data['first_update'] = true;

        }

        // Reference
        $ref = "ref=".$member['username'];
        $data['ref_param'] = $ref;

        \cryptor::setKey(env('CRYPTOR_SALT_FRONT'), env('CRYPTOR_KEY_FRONT'));
        $encrypt = \cryptor::encrypt($member_id."_".$login_key);
        $data['key_change'] = $encrypt;
        $data['key_change_en'] = \cryptor::decrypt($encrypt);

        \cryptor::setKey(env('CRYPTOR_SALT_WALLET'), env('CRYPTOR_KEY_WALLET'));
        $encrypt = \cryptor::encrypt($member_id."_".$login_key);
        $data['token_wallet'] = $encrypt;
        $data['token_wallet_en'] = \cryptor::decrypt($encrypt);

        return $this->success(0, $data);
    }

    public function firstUpdate(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'token' => 'required',
            'username' => 'required',
            'password' => 'required'
        ]);
        if($validator->fails()){
            return $this->error(103, $validator->errors());
        }

        \cryptor::setKey(env('CRYPTOR_SALT_FRONT'), env('CRYPTOR_KEY_FRONT'));
        $key = \cryptor::decrypt($input['token']);
        $ekey = explode("_", $key);
        if(!isset($ekey[1])){
            return $this->error(104);
        }
        $member_id = $ekey[0];
        $key_change = $ekey[1];

        // Check key and member_id
        $member = Members::where('id', $member_id)
            ->where('key_change', $key_change)
            ->select('id','name','username','old_id','first_update','first_update_at')
            ->first();
        if(!$member){
            return $this->error(104);
        }

        // check username exist
        $ck_username = Members::where('username', $input['username'])->count();
        if($ck_username > 0){
            return $this->error(106);
        }

        $datetime = date('Y-m-d H:i:s');
        $arrData = [
            'username' => $input['username'],
            'password' => Hash::make($input['password']),
            'first_update' => 1,
            'first_update_at' => $datetime,
        ];

        $repository = $this->getRepository(Members::class);

        $entity = $repository->findWithoutFail($member_id);

        $entity = $repository->updateEntity($arrData, $entity);

        $data = [
            'username' => $entity->username,
            'updated_at' => $datetime
        ];

        return $this->success(0, $data);

    }

    public function checkExist(Request $request){
        $param = $request->all();

        $validator = Validator::make($param, [
            'type' => 'required',
            'value' => 'required',
            'pid' => 'required',
        ]);
        if($validator->fails()){
            return $this->error(403, $validator->errors());
        }

        if($param['type'] == 'phone'){
            $len = strlen($param['value']);
            $ckphone = preg_match_all('/^\d{10}$/', $param['value']);
            if(!$ckphone){
                return $this->error(112);
            }
        }

        $exist = Members::where('core_agents.partner_id', $param['pid'])
            ->leftJoin('core_agents', 'core_agents.id', '=', 'member_members.agent_id')
            ->when($param, function ($query) use($param){
                if($param['type'] == 'phone'){
                    $query->where('member_members.phone', $param['value']);
                }
                if($param['type'] == 'username'){
                    $query->where('member_members.username', $param['value']);
                }
            })
            ->count();

        if($exist > 0){
            return $this->error(111);
        }

        return $this->success(0);

    }

    public function register(Request $request){

        $param = $request->all();

        $validator = Validator::make($param, [
            'username' => 'required',
            'password' => 'required',
            'agent' => 'required',
            'phone' => 'required',
            'bank_id' => 'required',
            'bank_account' => 'required',
            'bank_number' => 'required',
        ]);

        $ckphone = preg_match_all('/^\d{10}$/', $param['phone']);
        if(!$ckphone){
            return $this->error(112);
        }

        // Check bank 10 Digit
        // Check if GSB bank
        if($param['bank_id'] == 15){
            $ckbank = preg_match_all('/^\d{12}$/', $param['bank_number']);
            if(!$ckbank){
                return $this->error(114);
            }
        }else{
            $ckbank = preg_match_all('/^\d{10}$/', $param['bank_number']);
            if(!$ckbank){
                return $this->error(113);
            }
        }

        if($validator->fails()){
            return $this->error(403, $validator->errors());
        }

        // Check Exist Username
        $exist = Members::where('username', $param['username'])->count();
        if($exist){
            return $this->error(106);
        }

        // Check Agent
        $agent = Agents::where('ref', $param['agent'])->first();
        if(!$agent){
            return $this->error(109);
        }

        // Check Ref
        $ref = null;
        if(!empty($param['ref']) || isset($param['ref'])) {
            $ref = Members::where('username', $param['ref'])->first();
        }

        $member = [
            'agent_id' => $agent->id,
            'ref_member_id' => (!empty($ref)) ? $ref->id : null,
            'username' => $param['username'],
            'password' => Hash::make($param['password']),
            'password_key' => Crypt::encryptString(rand12(6)),
            'name' => $param['bank_account'],
            'phone' => $param['phone'],
            'lineid' => (!empty($param['lineid'])) ? $param['lineid'] : null,
            'howtoknow' => (!empty($param['howtoknow'])) ? $param['howtoknow'] : null,
            'status_id' => 1,
            'first_update' => 1,
            'first_update_at' => date('Y-m-d H:i:s'),
            'company_id' => $agent->company_id
        ];
        $repository = $this->getRepository(Members::class);
        $member = $repository->createEntity($member, App::make(Members::class));

        $bank = [
            'member_id' => $member->id,
            'bank_id' => $param['bank_id'],
            'bank_account' => $param['bank_account'],
            'bank_number' => $param['bank_number'],
            'is_main' => 1,
            'is_active' => 1,
            'company_id' => $agent->company_id
        ];
        $bank = $repository->createEntity($bank, App::make(MembersBanks::class));

        return $this->success(0, compact('member', 'bank'));

    }

    public function username(Request $request){

        $input = $request->input();

        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'pid' => 'required',
        ]);

        if($validator->fails()){
            return $this->error(403, $validator->errors());
        }

        $token = $this->mathcKey($input['token'], 'key_change', 'front');
        if($token['codeid'] != 0){
            return $this->errorMsg(406);
        }

        $games = Games::where('is_active', 1)
            ->orderBy('sort', 'asc')
            ->get();

        $arrData = [];
        foreach ($games as $game){

            $username = Username::where('core_username.member_id', $token['data']['member_id'])
                ->leftJoin('core_boards', 'core_boards.id', '=', 'core_username.board_id')
                ->where('core_username.is_active', 1)
                ->where('core_username.is_created', 1)
                ->where('core_boards.is_active', 1)
                ->where('core_boards.game_id', $game->id)
                ->select(
                    'core_username.id',
                    'core_username.board_id',
                    'core_username.username',
                    'core_boards.id AS board_id',
                    'core_boards.name AS board_name'
                )
                ->get();

            $arrData[] = [
                'game_id' => $game->id,
                'game_name' => $game->name,
                'game_api' => ($game->is_api) ? true : false,
                'mobile' => ($game->m_version) ? true : false,
                'username' => $username
            ];

        }

//        $arrData['records'] = count($json['data']);
//        $arrData['list'] = $json['data'];

        return $this->success(0, $arrData);

    }

    public function GenerateUsername(Request $request){

        $input = $request->input();

        $validator = Validator::make($request->all(), [
            'member_id' => 'required',
        ]);

        if($validator->fails()){
            return $this->error(403, $validator->errors());
        }

        $m = new \Modules\Member\Members\Http\Controllers\MembersController();
        $g = $m->genUsernameApi($input['member_id'], null, true, false);

        return $g;

    }

    public function username_old(Request $request){

        $input = $request->input();

        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'domain' => 'required',
        ]);

        if($validator->fails()){
            return $this->error(403, $validator->errors());
        }

        $token = $this->mathcKey($input['token'], 'key_change', 'front');
        if($token['codeid'] != 0){
            return $this->errorMsg(406);
        }

        $data = [
            'member_id' => $token['data']['member_id'],
            'domain' => $input['domain']
        ];

        $url = 'https://admin.wpnadmin.com/v1/username/';
        $username = $this->getCURL($url, 'POST', $data);

        $json = json_decode($username, true);

        if($json['codeid'] != 200){

            return $this->error(4, $json);

        }

        $arrData['records'] = count($json['data']);
        $arrData['list'] = $json['data'];

        return $this->success(0, $arrData);

    }

    public function confirmAuto(Request $request){

        $input = $request->input();

        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'id' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return $this->error(403, $validator->errors());
        }

        $token = $this->mathcKey($input['token'], 'key_change', 'front');
        if($token['codeid'] != 0){
            return $this->errorMsg(406);
        }

        $password = encrypter('encrypt', $input['password']);
        $data = [
            'confirmed_auto' => 1,
            'confirmed_password' => $password,
            'confirmed_auto_at' => $this->datetime
        ];

        \App\Models\Old\Username::where('A_I', $input['id'])->update($data);

        return $this->success(0);

    }


    public function info(Request $request){

        $input = $request->input();

        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if($validator->fails()){
            return $this->error(403, $validator->errors());
        }

        $token = $this->mathcKey($input['token'], 'key_change', 'front');
        if($token['codeid'] != 0){
            return $this->errorMsg(406);
        }

        $member_id = $token['data']['member_id'];

        $member = Members::where('id', $member_id)
            ->with(['membersAgent' => function($q){

            }, 'membersAgent.agentsPartner' => function($q){

            }])
            ->with(['membersStatus' => function($q){

            }])
            ->with(['banksMember' => function($q){
                $q->where('is_active', 1);
            }, 'banksMember.banksBank' => function($q){

            }])
            ->first();

        if(!$member){
            return $this->error(110);
        }

        $member = $member->toArray();

        $bank_to = ['bank_to' => null];
        if(isset($member['members_agent']['agents_partner']['id'])){
            // if Kbank KTB SCB
            if(!in_array($member['banks_member']['bank_id'], [2,3,6])){
                $to_bank = $member['banks_member']['bank_id'];
            }else{
                $to_bank = 6;
            }

            // Check bank to transfer
            $bank = BanksPartners::where('partner_id', $member['members_agent']['agents_partner']['id'])
                ->join('core_banks', 'core_banks.id', '=', 'core_banks_partners.bank_id')
                ->where('core_banks_partners.member_status_id', $member['status_id'])
                ->where('core_banks.is_active', 1)
                ->where('core_banks.bank_id', $to_bank)
                ->with(['banksBank' => function($q){
                    $q->where('is_active', 1)->select('id', 'bank_id', 'account', 'number', 'is_active');
                }, 'banksBank.banks' => function($q){
                    $q->select('id', 'code', 'name');
                }])
                ->where('core_banks_partners.is_active', 1)
                ->select(
                    'core_banks_partners.*',
                    'core_banks.bank_id AS b_bank_id'
                )
                ->first();

            if($bank){
                $bank = $bank->toArray();
                $bank_to = ['bank_to' => $bank];
            }
        }

        $resArr = array_merge($member, $bank_to);

        return $this->success(0, $resArr);

    }

    public function deposit(Request $request){

        $input = $request->input();

        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'topup_from' => 'required',
            'amount' => 'required',
            'topup_pay_at' => 'required',
            'topup_from_bank' => 'required',
            'topup_to_bank' =>  'required'
        ]);

        if($validator->fails()){
            return $this->error(403, $validator->errors());
        }

        $token = $this->mathcKey($input['token'], 'key_change', 'front');
        if($token['codeid'] != 0){
            return $this->errorMsg(406);
        }

        $member_id = $token['data']['member_id'];

        $member = Members::findOrFail($member_id);

        $dataJob = [
            'member_id' => $member_id,
            'topup_pay_at' => $input['topup_pay_at'],
            'topup_from_bank' => $input['topup_from_bank'],
            'topup_to_bank' => $input['topup_to_bank'],
            'amount' => $input['amount'],
            'status_id' => 1,
            'company_id' => $member->company_id
        ];

        $job = new JobsController();
        $order = $job->createJob($dataJob, 1);

        if(!$order['status']){
            return $this->error(801);
        }

        return $this->success(0, [], 'ได้รับรายการเรียบร้อย เจ้าหน้าที่กำลังตรวจสอบ...');

    }

    public function withdraw(Request $request){

        $input = $request->input();

        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'withdraw_to_bank' => 'required',
            'amount' => 'required'
        ]);

        if($validator->fails()){
            return $this->error(403, $validator->errors());
        }

        $token = $this->mathcKey($input['token'], 'key_change', 'front');
        if($token['codeid'] != 0){
            return $this->errorMsg(406);
        }

        $member_id = $token['data']['member_id'];

        $member = Members::findOrFail($member_id);

        $dataJob = [
            'member_id' => $member_id,
            'withdraw_to_bank' => $input['withdraw_to_bank'],
            'amount' => $input['amount'],
            'status_id' => 1,
            'company_id' => $member->company_id
        ];

        $job = new JobsController();
        $order = $job->createJob($dataJob, 2);

        if(!$order['status']){
            return $this->error(801);
        }

        return $this->success(0, [], 'ได้รับรายการเรียบร้อย เจ้าหน้าที่กำลังตรวจสอบ...');

    }

    public function transferLog(Request $request){

        $input = $request->input();

        $last_seven = date('Y-m-d', strtotime('-7 days', strtotime(date('Y-m-d'))));

        $validator = Validator::make($request->all(), [
            'token' => 'required',
            // 'group' => 'required', // 1=statement, 2=order, 3=event
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

        $member_id = $token['data']['member_id'];

        $transactions = Jobs::where('member_id', $member_id)
            // ->whereIn('group_id', $input['group'])
            ->whereNotIn('status_id', [6,7])
            ->whereRaw('DATE(created_at) >= ? AND DATE(created_at) <= ?', [$input['startDate'], $input['endDate']])
            ->with(['jobsStatus' => function($query){
                $query->select('id', 'name');
            }])
            ->with(['jobsType' => function($query){
                $query->select('id', 'name');
            }])
            ->orderby('id', 'desc')
            ->get()
            ->toArray();

        $arrData = [];
        $arrRow = [];

        $arrData['id'] = $member_id;
        $arrData['records'] = count($transactions);

        foreach ($transactions as $transaction){

            $arrRow[] = [
                'id' => $transaction['id'],
                'status_id' => $transaction['jobs_status']['id'],
                'status' => $transaction['jobs_status']['name'],
                'ref_job_id' => $transaction['ref_job_id'],
                'method' => (isset(parent::JOB_TYPE[$transaction['type_id'].$transaction['transfer_type']])) ? parent::JOB_TYPE[$transaction['type_id'].$transaction['transfer_type']] : "N/A",
                'type_id' => $transaction['type_id'],
                'username' => $transaction['username'],
                'amount' => $transaction['amount'],
                'created_at' => $transaction['created_at'],
            ];
        }

        $arrData['list'] = $arrRow;

        return $arrData;

    }

}