<?php

namespace Modules\Upc\Http\Controllers\V1;

use app\cryptor;
use App\Models\LoginLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Modules\Api\Http\Controllers\Game\TransferApiController;
use Modules\Core\Agents\Entities\Agents;
use Modules\Core\Games\Entities\Games;
use Modules\Core\Partners\Entities\Partners;
use Modules\Core\Username\Entities\Username;
use Modules\Member\Members\Entities\Members;
use Modules\Member\Members\Entities\MembersBanks;
use Modules\Platform\User\Entities\User;
use Modules\Upc\Http\Controllers\UpcController;

use App\Http\Controllers\Games\Dg\MemberController AS DG;
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
class UsernamesController extends UpcController
{

    protected $entityClass = Username::class;

    protected $apiUrl;

    public function getTransfer(Request $request){

        $input = $request->input();

        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'username' => 'required',
        ]);

        if($validator->fails()){
            return $this->error(403, $validator->errors());
        }

        $token = $this->mathcKey($input['token'], 'key_change', 'front');
        if($token['codeid'] != 0){
            return $this->errorMsg(406);
        }

        $username = Username::where('username', $input['username'])
            ->with(['usernameBoard' => function(){

            }, 'usernameBoard.boardsGame' => function(){

            }])
            ->where('member_id', $token['data']['member_id'])
            ->first();

        if(!$username){
            return $this->errorMsg(108);
        }

        $username = $username->toArray();

        if(empty($username['username_board']['boards_game']['api_url'])){
            $transfer = $this->apiTransfer($username);

            // return $transfer;

            $credit = 0;
            $outstanding = 0;
            $betcredit = 0;

            if($transfer['responseStatus']['code'] != 200){

                if($username['username_board']['boards_game']['code'] == 'ibc' && $transfer['responseStatus']['messageDetails'] == 'MEMBER_NOT_FOUND'){

                }else{
                    return $this->error(203, $transfer['responseStatus']['messageDetails']);
                }

            }else{

                $credit = $transfer['responseDetails']['banlance'];
                $outstanding = $transfer['responseDetails']['playing'];
                $betcredit = $transfer['responseDetails']['netbanlance'];

            }

            $arrRes = [
                'username' => $input['username'],
                'credit' => $credit,
                'outstanding' => $outstanding,
                'betcredit' => $betcredit,
            ];
        }else{
            return $this->error(202);
        }


        return $this->success(0, $arrRes);


    }

    public function apiTransfer($data, $type = 'detail'){

        $arrTrans = [
            'action' => 'transfer',
            'custid' => $data['username'],
            'type' => $type,
            'amount' => 0,
            'local_ip' => get_client_ip(),
            'auto' => true,
        ];

        $trans = new TransferApiController();
        $response = $trans->transfer($arrTrans);

        return $response;

    }

    public function trnfTransfer($data){

        $arrData = [
            'username' => 'testapi',
            'apikey' => 'wBNVoFFrYUzEzuJgfEMj/HvBTal5KafIwBn14dIIcUo=',
            'staffid' => '0',
            'from' => 'member'
        ];

        return $data_post = array_merge($arrData, $data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json'
        ));
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.103 Safari/537.36');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $data = curl_exec($ch);

        $json = json_decode($data, true);

        return $json;

    }

    public function gameLogin(Request $request)
    {

        $data = $request->input();

        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'game_id' => 'required',
            'ip' => 'required',
        ]);

        if($validator->fails()){
            return $this->error(403, $validator->errors());
        }

        $token = $this->mathcKey($data['token'], 'key_change', 'front');
        if($token['codeid'] != 0){
            return $this->error(406);
        }

        $member_id = $token['data']['member_id'];
        $game_id = $data['game_id'];
        $ip = $data['ip'];

        $repository = $this->getRepository(LoginLogs::class);

        // Created Login Log
        $arrLog = [
            'request_data' => json_encode($data, JSON_UNESCAPED_UNICODE),
            'request_at' => date('Y-m-d H:i:s'),
            'ip_address' => (isset($ip)) ? $ip : null
        ];
        $logs = $repository->createEntity($arrLog, \App::make(LoginLogs::class));

        // check username of member from game code
        $username = Username::where('member_id', $member_id)
            ->leftJoin('core_boards', 'core_username.board_id', '=', 'core_boards.id')
            ->leftJoin('core_partners', 'core_boards.partner_id', '=', 'core_partners.id')
            ->leftJoin('core_games', 'core_boards.game_id', '=', 'core_games.id')
            ->leftJoin('member_members', 'core_username.member_id', '=', 'member_members.id')
            ->where('core_boards.game_id', $game_id)
            ->whereNull('core_boards.for_event')
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
            return $this->error(508, [], 'ท่านยังไม่มี Username ของเว็บเกมส์นี้ กรุณาทำรายการฝาก');
        }

        if($username['is_maintenance']){
            $m_notes = (!empty($username['maintenance_notes'])) ? $username['maintenance_notes'] : 'ขณะนี้ระบบกำลังอยู่ระหว่างปิดปรับปรุงค่ะ.';
            return $this->error(508, [], $m_notes);
        }

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

            return $this->success(0, $res);

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

            if(!isset($data['platform']) || $data['platform'] == "d"){
                $res =$api->actionGet($setParam, 'launchGames.aspx');
            }else{
                $res =$api->actionGet($setParam, 'launchGames.aspx', [], ['html5' => 1]);
            }

            if($res['errCode'] != 0){
                return $this->error(508, [], $res['errMsg']);
            }

            $res['playUrl'] = str_replace("lang=en", "lang=th", $res['gameUrl']);
            $res['playUrlMobile'] = str_replace("lang=en", "lang=th", $res['gameUrl']);

            // Update Logs
            LoginLogs::where('id', $logs->id)->update(['response_data' => json_encode($res, JSON_UNESCAPED_UNICODE), 'response_at' => date('Y-m-d H:i:s')]);

            return $this->success(0, $res);

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
                return $this->error(508, $res['msg']);
            }

            $res['playUrl'] = $res['url'];
            $res['playUrlMobile'] = $res['url'];

            // Update Logs
            LoginLogs::where('id', $logs->id)->update(['response_data' => json_encode($res, JSON_UNESCAPED_UNICODE), 'response_at' => date('Y-m-d H:i:s')]);

            return $this->success(0, $res);

        }

        /**
         * Sbobet API
         */
        elseif($username['code'] == "sboapi"){

            $api = new SBO($key);

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

            return $this->success(0, $res);

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
                return $this->error(508, $res['msg']);
            }

            $res['playUrl'] = $api->gameUrl.$res['login_token'];
            $res['playUrlMobile'] = $api->gameUrl.$res['login_token'];

            // Update Logs
            LoginLogs::where('id', $logs->id)->update(['response_data' => json_encode($res, JSON_UNESCAPED_UNICODE), 'response_at' => date('Y-m-d H:i:s')]);

            return $this->success(0, $res);

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

            return $this->success(0, $res);

        }

        /**
         * Ufa
         */
        elseif(in_array($username['code'], ['ufa', 'lga', 'vga', 'gcb', 'hol'])){

            $random = 'algwva6ht9';
            $gameUrl = "https://".$random.".vvzzww.com/gameauth/";

            $token = [
                'game' => $username['code'],
                'password' => Crypt::decryptString($username['password']),
                'username' => $username['username'],
                'time' => time()
            ];
            $encode = encrypter('encrypt', json_encode($token, JSON_UNESCAPED_UNICODE), config('app.key_api_admin'), config('app.salt_api_admin'));
            $res['playUrl'] = $gameUrl."?token=".$encode."&device=d";
            $res['playUrlMobile'] = $gameUrl."?token=".$encode."&device=m";

            // Update Logs
            LoginLogs::where('id', $logs->id)->update(['response_data' => json_encode($res, JSON_UNESCAPED_UNICODE), 'response_at' => date('Y-m-d H:i:s')]);

            return $this->success(0, $res);

        }

    }

}