<?php

namespace Modules\Api\Http\Controllers\Member;


use App\Models\LoginLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Modules\Api\Http\Controllers\Base\CrudApiController;
use Modules\Core\Username\Entities\Username;
use Modules\Member\Members\Entities\Members;
use Modules\Member\Members\Http\Controllers\MembersController;
use Modules\Member\Members\Http\Requests\MembersRequest;
use App\Models\Old\Members AS OldMember;
use App\Http\Controllers\Games\Dg\MemberController AS DG;
use App\Http\Controllers\Games\Og\MemberController AS OG;
use App\Http\Controllers\Games\Sa\MemberController AS SA;
use App\Http\Controllers\Games\Aec\MemberController AS AEC;
use App\Http\Controllers\Games\Sbo\MemberController AS SBO;
use App\Http\Controllers\Games\Sexy\MemberController AS SEXY;
use App\Http\Controllers\Games\Ibc\MemberController AS IBC;
use App\Http\Controllers\Games\Lotto\MemberController AS LOTTO;
use App\Http\Controllers\Games\Csh\MemberController AS CSH;
use App\Http\Controllers\Games\Tfg\MemberController AS TFG;

/**
 *
 * Class ContactsApiController
 *
 * @package Modules\Api\Http\Controllers
 */
class MembersApiController extends CrudApiController
{

    protected $entityClass = Members::class;

    protected $moduleName = 'memberMembers';

    protected $languageFile = 'member/members::members';

    protected $with = [
        'membersStatus',
        'usernameMember',
        'banksMember'
    ];

    protected $permissions = [
        'browse' => 'member.members.browse',
        'create' => 'member.members.create',
        'update' => 'member.members.update',
        'destroy' => 'member.members.destroy'
    ];

    protected $showRoute = 'member.members.show';

    protected $storeRequest = MembersRequest::class;

    protected $updateRequest = MembersRequest::class;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Before entity store
     * @param $request
     * @param $entity
     * @return bool
     */
    public function beforeStoreInput($request, &$input)
    {
        $password = Hash::make($request['password']);
        $input['password'] = $password;
    }

    public function addMember($id)
    {
        $base = new MembersController();
        return $base->addMember($id);
    }

    public function genUsername($id, $order)
    {
        $base = new MembersController();
        return $base->genUsernameApi($id, $order);
    }

    public function memberLogin(Request $request)
    {

        $data = $request->all();

        if(!isset($data['username']) || $data['username'] == ""){
            return $this->respond(false, [], ['error' => 'please_put_username'], ['message' => 'กรุณาใส่ Username ของท่าน']);
        }
        if(!isset($data['password']) || $data['password'] == ""){
            return $this->respond(false, [], ['error' => 'please_put_password'], ['message' => 'กรุณาใส่ Password ของท่าน']);
        }
        if(!isset($data['game']) || $data['game'] == ""){
            return $this->respond(false, [], ['error' => 'please_put_game_code'], ['message' => 'กรุณาใส่ Game Code ของท่าน']);
        }
        if(!isset($data['domain']) || $data['domain'] == ""){
            return $this->respond(false, [], ['error' => 'please_put_domain'], ['message' => 'กรุณาระบุ Domain ที่ท่านใช้บริการ']);
        }

        $repository = $this->getRepository();

        // Created Login Log
        $arrLog = [
            'member_user' => $data['username'],
            'member_pass' => $data['password'],
            'request_data' => json_encode($data, JSON_UNESCAPED_UNICODE),
            'request_at' => date('Y-m-d H:i:s'),
            'ip_address' => (isset($data['ip'])) ? $data['ip'] : null
        ];
        $logs = $repository->createEntity($arrLog, \App::make(LoginLogs::class));


        // check member login from admin.wpnadmin.com
        $member = OldMember::where('member.username', $data['username'])
            ->leftJoin('domain', 'member.domain', '=', 'domain.id')
            ->where('member.domain', $data['domain'])
            ->select(
                'member.A_I',
                'member.password',
                'member.new_id',
                'domain.new_id AS d_new_id'
            )
            ->first();

        if($member){

            $member = $member->toArray();

            if($member['password'] != $data['password']){
                return $this->respond(false, [], ['error' => 'don\'t_have_member'], ['message' => 'รหัสผ่านไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง']);
            }

            if(empty($member['new_id'])){
                return $this->respond(false, [], ['error' => 'don\'t_have_username'], ['message' => 'ท่านยังไม่มี Username ของเว็บเกมส์นี้ กรุณาทำรายการฝาก']);
            }

            $member_id = $member['new_id'];

        }else {

            $member = Members::where('username', $data['username'])->first();
            $member_id = $member->id;

            if (!$member) {
                return $this->respond(false, [], ['error' => 'don\'t_have_member'], ['message' => 'ท่านยังไม่ได้เป็นสมาชิกกับเรา']);
            }

            if (!Hash::check($data['password'], $member->password)) {
                return $this->respond(false, [], ['error' => 'don\'t_have_member'], ['message' => 'รหัสผ่านไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง']);
            }
        }

//        $n_username = $data['domain']."_".$data['username'];
//        $member = Members::where('username', $n_username)->first();
//
//        if(!$member){
//            $member = Members::where('username', $data['username'])->first();
//        }
//
//        if (!$member) {
//            return $this->respond(false, [], ['error' => 'don\'t_have_member'], ['message' => 'ท่านยังไม่ได้เป็นสมาชิกกับเรา']);
//        }
//        if (!Hash::check($data['password'], $member->password)) {
//            return $this->respond(false, [], ['error' => 'don\'t_have_member'], ['message' => 'รหัสผ่านไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง']);
//        }
//        $member_id = $member->id;


        // check username of member from game code
        $username = Username::where('member_id', $member_id)
            ->leftJoin('core_boards', 'core_username.board_id', '=', 'core_boards.id')
            ->leftJoin('core_partners', 'core_boards.partner_id', '=', 'core_partners.id')
            ->leftJoin('core_games', 'core_boards.game_id', '=', 'core_games.id')
            ->leftJoin('member_members', 'core_username.member_id', '=', 'member_members.id')
            ->where('core_games.old_id', $data['game'])
            ->whereNull('core_boards.for_event')
            ->where('core_partners.old_id', $data['domain']) // Check Domain from Old Admin
            ->select(
                'core_username.*',
                'core_boards.name',
                'core_boards.api_code',
                'core_games.name',
                'core_games.code',
                'core_games.is_maintenance',
                'core_games.maintenance_notes',
                'member_members.username AS mem_username'
            )
            ->first();

        if(!$username){
            return $this->respond(false, [], ['error' => 'dont_have_username'], ['message' => 'ท่านยังไม่มี Username ของเว็บเกมส์นี้ กรุณาทำรายการฝาก']);
        }

        if($username['is_maintenance']){
            $m_notes = (!empty($username['maintenance_notes'])) ? $username['maintenance_notes'] : 'ขณะนี้ระบบกำลังอยู่ระหว่างปิดปรับปรุงค่ะ.';
            return $this->respond(false, [], ['error' => 'game_is_maintenance'], ['message' => $m_notes]);
        }

//        print_r($username);
//        exit;

        // Update Logs
        LoginLogs::where('id', $logs->id)->update(['member_id' => $member_id, 'member_username' => $username['mem_username'], 'username_id' => $username['id'], 'username' => $username['username']]);

        $key = json_decode($username['api_code'], true);

        if($username['code'] == "sa"){

            $api = new SA($key);
            $setParam = [
                'method' => 'LoginRequest',
                'Username' => $username['username'],
                'CurrencyType' => 'THB',
                'Lang' => 'th',
            ];
            $api->setParam($setParam);
            $response = $api->push();
            $res = xmlDecode($response, true);

            if($res['ErrorMsgId'] != 0){
                return $this->respond(false, [], ['error' => 'login_error'], ['message' => $res['ErrorMsg']]);
            }

            $res['playUrl'] = $api->clienturl.'?username='.$username['username'].'&token='.$res['Token'].'&lobby='.$api->lobby.'&lang=th&returnurl='.$data['origin_url'];
            $res['playUrlMobile'] = $api->clienturl.'?username='.$username['username'].'&token='.$res['Token'].'&lobby='.$api->lobby.'&lang=th&mobile=true&returnurl='.$data['origin_url'];

            return $this->respond(true, [$res], [], ['message' =>  trans('core::core.entity.records_found')]);

        }

        /**
         * Dream Gaming
        */
        elseif($username['code'] == "dg"){

            $api = new DG($key);
            $setParam = [
                'device' => '5',
                'lang' => 'th',
                'member' => [
                    'username' => $username['username']
                ]
            ];
            $api->setParam($setParam, 'user/login');
            $res = $api->push();

            if($res['codeId'] != 0){
                return $this->respond(false, [], ['error' => 'login_error'], ['message' => $api->getCode($res['codeId'])]);
            }

            $res['playUrl'] = $res['list'][0].$res['token'];
            $res['playUrlMobile'] = $res['list'][1].$res['token'];

            return $this->respond(true, [$res], [], ['message' =>  trans('core::core.entity.records_found')]);

        }

        /**
         * AECBET
        */
        elseif($username['code'] == "aec"){

            $api = new AEC($key);

            $setParam = [
                'username' => $username['username']
            ];
            $res =$api->saveUsername($setParam);

            if($res['error'] != 0){
                return $this->respond(false, [], ['error' => 'login_error'], ['message' => $api->getCode($res['error'])]);
            }

            $res['playUrl'] = $res['url'];
            $res['playUrlMobile'] = $res['urlMobile'];

            // Update Logs
            LoginLogs::where('id', $logs->id)->update(['response_data' => json_encode($res, JSON_UNESCAPED_UNICODE), 'response_at' => date('Y-m-d H:i:s')]);

            return $this->respond(true, [$res], [], ['message' =>  trans('core::core.entity.records_found')]);

        }

        /**
         * Sexy Baccarat
         */
        elseif($username['code'] == "sexy"){

            $api = new SEXY($key);
            $setParam = [
                'userId' => $username['username'],
            ];

            $res =$api->actionGet($setParam, 'wallet/login');

            if($res['status'] != 0000){
                return $this->respond(false, [], ['error' => 'login_error'], ['message' => $api->getCode($res['status'])]);
            }

            $res['playUrl'] = $res['url']."th";
            $res['playUrlMobile'] = str_replace("isMobileLogin=", "isMobileLogin=true", $res['url'])."th";

            return $this->respond(true, [$res], [], ['message' =>  trans('core::core.entity.records_found')]);

        }

        /**
         * IBC
         */
        elseif($username['code'] == "ibc"){

            $api = new IBC($key);
            $setParam = [
                'password' => Crypt::decryptString($username['password']),
                'providercode' => 'IB',
                'type' => 'SB',
                'username' => $username['username']
            ];

            if(!isset($data['type']) || $data['type'] == "d"){
                $res =$api->actionGet($setParam, 'launchGames.aspx');
            }else{
                $res =$api->actionGet($setParam, 'launchGames.aspx', [], ['html5' => 1]);
            }

            if($res['errCode'] != 0){
                return $this->respond(false, [], ['error' => 'login_error'], ['message' => $res['errMsg']]);
            }

//            // For Mobile
//            $resM =$api->actionGet($setParam, 'launchGames.aspx', [], ['html5' => 1]);
//            if($resM['errCode'] != 0){
//                return $this->respond(false, [], ['error' => 'login_error'], ['message' => $resM['errMsg']]);
//            }

            $res['playUrl'] = str_replace("lang=en", "lang=th", $res['gameUrl']);
            $res['playUrlMobile'] = str_replace("lang=en", "lang=th", $res['gameUrl']);

            // Update Logs
            LoginLogs::where('id', $logs->id)->update(['response_data' => json_encode($res, JSON_UNESCAPED_UNICODE), 'response_at' => date('Y-m-d H:i:s')]);

            return $this->respond(true, [$res], [], ['message' =>  trans('core::core.entity.records_found')]);

        }

        /**
         * LottoSH
         */
        elseif($username['code'] == "lottosh"){

            $api = new LOTTO($key);
            $setParam = [
                'username' => $username['username'],
                'redirect_url' => $api->redirect,
            ];

            $res =$api->actionPost($setParam, 'login');

            if($res['status'] != 'success'){
                return $this->respond(false, [], ['error' => 'login_error'], ['message' => $res['msg']]);
            }

            $res['playUrl'] = $res['url'];
            $res['playUrlMobile'] = $res['url'];

            // Update Logs
            LoginLogs::where('id', $logs->id)->update(['response_data' => json_encode($res, JSON_UNESCAPED_UNICODE), 'response_at' => date('Y-m-d H:i:s')]);

            return $this->respond(true, [$res], [], ['message' =>  trans('core::core.entity.records_found')]);

        }

        /**
         * Sbobet API
         */
        elseif($username['code'] == "sboapi"){

            $api = new SBO($key);

            if($data['game_type'] == "fullOld"){
                $token = [
                    'username' => $username['username'],
                    'expire' => time() + 10,
                    'key' => config('app.key_api_admin_member')
                ];
                //$res['token'] = json_encode($token, JSON_UNESCAPED_UNICODE);
                $encode = encrypter('encrypt', json_encode($token, JSON_UNESCAPED_UNICODE), config('app.key_api_admin'), config('app.salt_api_admin'));
                //$res['token_encode'] = $encode;
                //$res['token_decode'] = encrypter('decrypt', $encode, config('app.key_api_admin'), config('app.salt_api_admin'));;
                //$res['date'] = date('Y-m-d H:i:s');
                //$res['date_expire'] = date('Y-m-d H:i:s', $token['expire']);
                $res['playUrl'] = $api->gameUrl."?token=".$encode."&device=d";
                $res['playUrlMobile'] = $api->gameUrl."?token=".$encode."&device=m";

                // Update Logs
                LoginLogs::where('id', $logs->id)->update(['response_data' => json_encode($res, JSON_UNESCAPED_UNICODE), 'response_at' => date('Y-m-d H:i:s')]);

                return $this->respond(true, [$res], [], ['message' =>  trans('core::core.entity.records_found')]);
            }

            if($data['game_type'] == "full"){

                $random = rand12(16);
                $random = 'thulrrxpmjissrcv';
                $api->gameUrl = "https://".$random.".vvzzww.com/";

                $token = [
                    'username' => $username['username'],
                    'expire' => time() + 10,
                    'key' => config('app.key_api_admin_member')
                ];
                //$res['token'] = json_encode($token, JSON_UNESCAPED_UNICODE);
                $encode = encrypter('encrypt', json_encode($token, JSON_UNESCAPED_UNICODE), config('app.key_api_admin'), config('app.salt_api_admin'));
                //$res['token_encode'] = $encode;
                //$res['token_decode'] = encrypter('decrypt', $encode, config('app.key_api_admin'), config('app.salt_api_admin'));;
                //$res['date'] = date('Y-m-d H:i:s');
                //$res['date_expire'] = date('Y-m-d H:i:s', $token['expire']);
                $res['playUrl'] = $api->gameUrl."?token=".$encode."&device=d";
                $res['playUrlMobile'] = $api->gameUrl."?token=".$encode."&device=m";

                // Update Logs
                LoginLogs::where('id', $logs->id)->update(['response_data' => json_encode($res, JSON_UNESCAPED_UNICODE), 'response_at' => date('Y-m-d H:i:s')]);

                return $this->respond(true, [$res], [], ['message' =>  trans('core::core.entity.records_found')]);
            }

            // For Sbo
//            $data['user_game'] = $username['username'];
//            $res = $api->getLogin($data);
//
//            if($res['error']['id'] != 0){
//                return $this->respond(false, [], ['error' => 'login_error'], ['message' => $res['error']['msg']]);
//            }
//
//            return $this->respond(true, [$res], [], ['message' =>  trans('core::core.entity.records_found')]);

        }

        /**
         * Casino SH
         */
        elseif($username['code'] == "csh"){

            $api = new CSH($key);
            $setParam = [
                'username' => $username['username'],
            ];

            $api->apiUrl = 'https://kmo.psg777.com/api/';

//            if(!isset($data['debug']) || $data['debug'] == 1){
//                return $res =$api->actionPost($setParam, 'userLogin', true);
//            }

            $res =$api->actionPost($setParam, 'userLogin');

            if($res['status'] != 'success'){
                return $this->respond(false, [], ['error' => 'login_error'], ['message' => $res['msg']]);
            }

            $res['playUrl'] = $api->gameUrl.$res['login_token'];
            $res['playUrlMobile'] = $api->gameUrl.$res['login_token'];

            // Update Logs
            LoginLogs::where('id', $logs->id)->update(['response_data' => json_encode($res, JSON_UNESCAPED_UNICODE), 'response_at' => date('Y-m-d H:i:s')]);

            return $this->respond(true, [$res], [], ['message' =>  trans('core::core.entity.records_found')]);

        }


        /**
         * TFGaming
         */
        elseif($username['code'] == "tfg"){

            $api = new TFG($key);

            $res['playUrl'] = $api->gameUrl.$username['token'];
            $res['playUrlMobile'] = $api->gameUrl.$username['token'];

            // Update Logs
            LoginLogs::where('id', $logs->id)->update(['response_data' => json_encode($res, JSON_UNESCAPED_UNICODE), 'response_at' => date('Y-m-d H:i:s')]);

            return $this->respond(true, [$res], [], ['message' =>  trans('core::core.entity.records_found')]);

        }

    }

    public function usernameLogin($data, $username, $debug = false){

        $game_id = $username['username_board']['game_id'];
        $game_code = $username['username_board']['boards_game']['code'];

        $key = json_decode($username['username_board']['api_code'], true);
        /**
         * Casino SH
         */
        if($game_code == "csh"){

            $api = new CSH($key);

            if(empty($data['game']) || !isset($data['game'])) {
                $setParam = [
                    'username' => $data['username'],
                ];
            }else{
                $setParam = [
                    'username' => $data['username'],
                    'launchGame' => '{"game":"'.$data['game'].'","gamecode":"none","lang":"thai"}'
                ];
            }

            $api->apiUrl = 'https://kmo.psg777.com/api/';

            if($debug){
                return $res = $api->actionPost($setParam, 'userLogin', true);
            }else {
                $res = $api->actionPost($setParam, 'userLogin');
            }

            if($res['status'] != 'success'){
                return $this->respond(false, [], ['error' => 'login_error'], ['message' => $res['msg']]);
            }

            $res['playUrl'] = $api->gameUrl.$res['login_token'];
            $res['playUrlMobile'] = $api->gameUrl.$res['login_token'];

            return $this->respond(true, $res, [], ['message' =>  trans('core::core.entity.records_found')]);

        }

        /**
         * LottoSH
         */
        elseif($game_code == "lottosh"){

            $api = new LOTTO($key);
            $setParam = [
                'username' => $data['username'],
                'redirect_url' => $api->redirect,
            ];

            $res =$api->actionPost($setParam, 'login');

            if($res['status'] != 'success'){
                return $this->respond(false, [], ['error' => 'login_error'], ['message' => $res['msg']]);
            }

            $res['playUrl'] = $res['url'];
            $res['playUrlMobile'] = $res['url'];

            return $this->respond(true, $res, [], ['message' =>  trans('core::core.entity.records_found')]);

        }

        /**
         * Sbobet API
         */
        elseif($game_code == "sboapi"){

            $api = new SBO($key);
            $setParam = [
                'user_game' => $data['username'],
                'game_type' => 'SportsBook'
            ];

            $response = $api->getLogin($setParam);

            if($response['error']['id'] != 0){
                return $this->respond(false, [], ['error' => 'login_error'], ['message' => $response['error']['msg']]);
            }

            $res['playUrl'] = $response['playUrl'];
            $res['playUrlMobile'] = $response['playUrlMobile'];

            return $this->respond(true, $res, [], ['message' =>  trans('core::core.entity.records_found')]);

        }

        return $this->respond(false, [], ['error' => 'login_error'], ['message' => 'This provider not support for this username.']);

    }

    public function changePassword($data, $username, $debug = false){

        $game_id = $username['username_board']['game_id'];
        $game_code = $username['username_board']['boards_game']['code'];

        $key = json_decode($username['username_board']['api_code'], true);
        /**
         * Casino SH
         */
        if($game_code == "csh"){

            $api = new CSH($key);

            $setParam = [
                'id' => $username['ref_id'],
                'password' => $data['password'],
            ];

            $api->apiUrl = 'https://kmo.psg777.com/bo/editplayer/';

            if($debug){
                return $res = $api->actionPost($setParam, 'password', true);
            }else {
                $res = $api->actionPost($setParam, 'password');
            }

            if($res['status'] != 'success'){
                return $this->respond(false, [], ['error' => 'login_error'], ['message' => $res['msg']]);
            }

            return $this->respond(true, $res, [], ['message' =>  trans('core::core.entity.records_found')]);

        }

        return $this->respond(false, [], ['error' => 'login_error'], ['message' => 'This provider not support for this action.']);

    }

    public function memberLoginFun(Request $request)
    {

        $data = $request->all();

        if($data['game'] == "sa"){

            $api = new SA();
            $setParam = [
                'method' => 'LoginRequestForFun',
                'Amount' => '5000',
                'CurrencyType' => 'THB',
                'Lang' => 'th',
            ];
            $api->setParam($setParam);
            $response = $api->push();
            $res = xmlDecode($response, true);

            if($res['ErrorMsgId'] != 0){
                return $this->respond(false, [], ['error' => 'login_error'], ['message' => $res['ErrorMsg']]);
            }

            $res['playUrl'] = $api->clienturl.'?username='.$res['DisplayName'].'&token='.$res['Token'].'&lobby='.$api->lobby.'&lang=th&returnurl='.$data['origin_url'];
            $res['playUrlMobile'] = $api->clienturl.'?username='.$res['DisplayName'].'&token='.$res['Token'].'&lobby='.$api->lobby.'&lang=th&mobile=true&returnurl='.$data['origin_url'];

            return $this->respond(true, [$res], [], ['message' =>  trans('core::core.entity.records_found')]);

        }

        /**
         * Dream Gaming
         */
        elseif($data['game'] == "dg"){

            $api = new DG();
            $setParam = [
                'device' => '5',
                'lang' => 'th'
            ];
            $api->setParam($setParam, 'user/free');
            $res = $api->push();

            if($res['codeId'] != 0){
                return $this->respond(false, [], ['error' => 'login_error'], ['message' => $api->getCode($res['codeId'])]);
            }

            $res['playUrl'] = $res['list'][0].$res['token'];
            $res['playUrlMobile'] = $res['list'][1].$res['token'];

            return $this->respond(true, [$res], [], ['message' =>  trans('core::core.entity.records_found')]);

        }


    }

    public function memberLoginUsername(Request $request)
    {

        $data = $request->all();

        if(!isset($data['username']) || $data['username'] == ""){
            return $this->respond(false, [], ['error' => 'please_put_username'], ['message' => 'กรุณาใส่ Username ของท่าน']);
        }

        // check username of member from game code
        $username = Username::where('core_username.username', $data['username'])
            ->leftJoin('core_boards', 'core_username.board_id', '=', 'core_boards.id')
            ->leftJoin('core_games', 'core_boards.game_id', '=', 'core_games.id')
            ->leftJoin('member_members', 'core_username.member_id', '=', 'member_members.id')
            ->select(
                'core_username.*',
                'core_boards.name',
                'core_boards.api_code',
                'core_games.name',
                'core_games.code'
            )
            ->first();

        if(!$username){
            return $this->respond(false, [], ['error' => 'dont_have_username'], ['message' => 'ท่านยังไม่มี Username ของคาสิโนนี้ กรุณาทำรายการฝาก']);
        }

        $username = $username->toArray();

        $key = json_decode($username['api_code'], true);

        if($username['code'] == "sa"){

        }
        elseif($username['code'] == "sbo"){

            if(!isset($data['gameType']) || $data['gameType'] == ""){
                return $this->respond(false, [], ['error' => 'please_put_gameType'], ['message' => 'กรุณาระบุ gameType เพื่อเข้าเกมส์']);
            }

            $gameType = $data['gameType'];
            $api = new SBO($key);

            $res = $api->login($data['username'], $gameType);

            if($gameType == "SportsBook") {
                $res['playUrl'] = "http://sports-betfun777.sb88api.com/welcome2.aspx?token=" . $res['token'] . "&lang=th-th&oddstyle=MY&theme=SBO&oddsmode=double&device=d";
                $res['playUrlMobile'] = "http://sports-betfun777.sb88api.com/welcome2.aspx?token=" . $res['token'] . "&lang=th-th&oddstyle=MY&theme=SBO&oddsmode=double&device=m";
            }
            elseif($gameType == "VirtualSports"){
                $res['playUrl'] = "http://virtualsports-betfun777.sb88api.com/Home/Welcome?gmOnlineId=3458478272&token=" . $res['token'] . "&lang=en";
                $res['playUrlMobile'] = "http://virtualsports-betfun777.sb88api.com/Home/Welcome?gmOnlineId=3458478272&token=" . $res['token'] . "&lang=en";
            }
            elseif($gameType == "Casino"){
                $res['playUrl'] = "http://lobby-betfun777.sb88api.com/welcome.aspx?token=" . $res['token'] . "&locale=en&sb=api-betfun777.wecname.com&device=d";
                $res['playUrlMobile'] = "http://lobby-betfun777.sb88api.com/welcome.aspx?token=" . $res['token'] . "&locale=en&sb=api-betfun777.wecname.com&device=m";
            }
            elseif($gameType == "Games"){
                if(!isset($data['gameID']) || $data['gameID'] == ""){
                    return $this->respond(false, [], ['error' => 'please_put_gameID'], ['message' => 'กรุณาระบุ gameID เพื่อเข้าเกมส์']);
                }
                $gameID = $data['gameID'];
                $res['playUrl'] = "http://rng-betfun777.sb88api.com/web-root/public/?gameId=".$gameID."&token=".$res['token'];
                $res['playUrlMobile'] = "http://rng-betfun777.sb88api.com/web-root/public/?gameId=".$gameID."&token=".$res['token'];
            }

            return json_encode($res, JSON_UNESCAPED_SLASHES);

        }

        elseif($username['code'] == "dg"){



        }

        elseif($username['code'] == "og"){

            $api = new OG($key);
            $api->setToken();

//            $setParam = [
//                "rows" => 100,
//                "page" => 1,
//                "sidx" => "sidx",
//                "sord" => "asc"
//            ];
//            $api->setParam($setParam, 'players', false);
//            $response = $api->push(false);

            // Get Key Game
            $setParam = [
                "username" => "ogshs0000"
            ];
            $api->setParam($setParam, 'game-providers/1/games/oglive/key', false);
            $key = $api->push(false);
            if($key['status'] == "error"){
                return $this->respond(false, [], ['error' => 'cannot_login'], ['message' => 'ยังไม่สามารถ Login ได้ในขณะนี้ กรุณาลองใหม่อีกครั้ง']);
            }

            // Get URL
            $setParam = [
                "key" => $key['data']['key']
            ];
            $api->setParam($setParam, 'game-providers/1/play', false);
            $response = $api->push(false);

            print_r($response);

        }

    }

}