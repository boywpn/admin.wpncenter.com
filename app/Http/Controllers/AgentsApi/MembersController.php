<?php

namespace App\Http\Controllers\AgentsApi;

use App\Http\Controllers\Games\Csh\MemberController;
use App\Models\AgentTransfersLog;
use App\Models\TransfersAgentLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Modules\Api\Http\Controllers\Game\TransferApiController;
use Modules\Api\Http\Controllers\Member\MembersApiController;
use Modules\Core\Boards\Entities\Boards;
use Modules\Core\Games\Entities\Games;
use Modules\Core\Username\Entities\Username;
use Modules\Core\Username\Http\Controllers\UsernameController;

use App\Http\Controllers\Games\Ibc\BetsController AS IBC;
use App\Http\Controllers\Games\Csh\BetsController AS CSH;
use App\Http\Controllers\Games\Csh\MemberController AS MCSH;
use App\Http\Controllers\Games\Sbo\BetsController AS SBO;
use App\Http\Controllers\Games\Tfg\BetsController AS TFG;
use App\Http\Controllers\Games\Lotto\BetsController AS LOTTO;

class MembersController extends ApiController
{
    //
    public function regisUsername($data){

        $validator = Validator::make($data, [
            'agent' => 'required',
            'pcode' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return $this->error(201, $validator->errors());
        }

        $game = Games::where('code', $data['pcode'])
            ->with(['boardsGame' => function($query) use ($data){
                $query->where('agent_id', $this->agent_id)->where('member_prefix', $data['agent'])->where('is_active', 1)->select('*');
            }])
            ->first();

        if(!$game){
            return $this->error(201, [], 'The pcode invalid');
        }
        $game = $game->toArray();
        if(empty($game['boards_game'])){
            return $this->error(201, [], 'This game not available for this agent.');
        }

        $this->entityClass = Username::class;
        $repository = $this->getRepository();

        $board = $game['boards_game'][0];

        // Check username format
        if (strpos($data['username'], $board['member_prefix']) === false) {
            return $this->error(201, [], 'Username format not correct. Please check agent prefix.');
        }

        if(strlen($data['username']) > $this->username_length){
            return $this->error(201, [], 'Username should be less than or equal '.$this->username_length.' character.');
        }

        $username = $data['username'];
        $password = Crypt::encryptString($data['password']);

        // Check exist
        $entity = Username::where('username', $username)->first();
        if(!$entity){
            $dataUsername = [
                'board_id' => $board['id'],
                'username' => $username,
                'password' => $password,
                'is_active' => 1,
                'company_id' => $this->agent['company_id'],
            ];
            $entity = $repository->createEntity($dataUsername, \App::make(Username::class));
        }else{
            if($entity->is_created == 1){
                return $this->error(201, [], 'Username is exist.');
            }
        }

        // Created username on provider
        $u = new UsernameController();
        $res = $u->pushUsernameApi($entity->id);

//        $response = compact('entity', 'res');

        if(!$res['status']){
            return $this->error(201, $res['message'], 'Register not success!');
        }

        return $this->success(0, $res['message'], 'Register Success!');

    }

    public function regisUsernameTest($data){

        $validator = Validator::make($data, [
            'agent' => 'required',
            'pcode' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return $this->error(201, $validator->errors());
        }

        return $game = Games::where('code', $data['pcode'])
            ->with(['boardsGame' => function($query) use ($data){
                $query->where('agent_id', $this->agent_id)->where('member_prefix', $data['agent'])->where('is_active', 1)->select('*');
            }])
            ->first();

        if(!$game){
            return $this->error(201, [], 'The pcode invalid');
        }
        $game = $game->toArray();
        if(empty($game['boards_game'])){
            return $this->error(201, [], 'This game not available for this agent.');
        }

        $this->entityClass = Username::class;
        $repository = $this->getRepository();

        $board = $game['boards_game'][0];

        // Check username format
        if (strpos($data['username'], $board['member_prefix']) === false) {
            return $this->error(201, [], 'Username format not correct. Please check agent prefix.');
        }

        if(strlen($data['username']) > $this->username_length){
            return $this->error(201, [], 'Username should be less than or equal '.$this->username_length.' character.');
        }

        $username = $data['username'];
        $password = Crypt::encryptString($data['password']);

        // Check exist
        $entity = Username::where('username', $username)->first();
        if(!$entity){
            $dataUsername = [
                'board_id' => $board['id'],
                'username' => $username,
                'password' => $password,
                'is_active' => 1,
                'company_id' => $this->agent['company_id'],
            ];
            $entity = $repository->createEntity($dataUsername, \App::make(Username::class));
        }else{
            if($entity->is_created == 1){
                return $this->error(201, [], 'Username is exist.');
            }
        }

        // Created username on provider
        $u = new UsernameController();
        $res = $u->pushUsernameApi($entity->id);

//        $response = compact('entity', 'res');

        if(!$res['status']){
            return $this->error(201, $res['message'], 'Register not success!');
        }

        return $this->success(0, $res['message'], 'Register Success!');

    }

    public function transferCredit($data){

        $validator = Validator::make($data, [
            'username' => 'required',
            'amount' => 'required',
            'refno' => 'required',
        ]);

        if($validator->fails()){
            return $this->error(201, $validator->errors());
        }

        // Check Username
        $username = Username::where('username', $data['username'])->first();
        if(!$username){
            return $this->error(201, [], 'Username doesn\'t have.');
        }

        $this->entityClass = AgentTransfersLog::class;
        $repository = $this->getRepository();

        $arrLog = [
            'agent_id' => $this->agent_id,
            'board_id' => $username->board_id,
            'username_id' => $username->id,
            'username' => $username->username,
            'amount' => $data['amount'],
            'refno' => $data['refno']
        ];
        $entity = $repository->createEntity($arrLog, \App::make(AgentTransfersLog::class));

        $arrTrans = [
            'log_agent_id' => $this->agent_id,
            'action' => 'transfer',
            'orderid' => 'agent_transfer',
            'custid' => $data['username'],
            'type' => 'transfer',
            'amount' => (string)round($data['amount'], 2),
            'staffid' => 1,
            'from' => 'api_agent_transfer',
            'stateid' => $entity->id,
            'local_ip' => get_client_ip(),
            'auto' => true,
        ];

        $arrRequest = [
            'request_at' => date('Y-m-d H:i:s'),
            'request_data' => json_encode($arrTrans, JSON_UNESCAPED_UNICODE),
        ];

        $repository->updateEntity($arrRequest, $entity);

        $arrTrans['refno'] = $data['refno'];

        $trans = new TransferApiController();
        $response = $trans->transfer($arrTrans);

        $arrResponse = [
            'response_at' => date('Y-m-d H:i:s'),
            'response_data' => json_encode($response, JSON_UNESCAPED_UNICODE),
            'response_ref' => ($response['responseStatus']['code'] == 200) ? $response['responseDetails']['order_api_id'] : null,
            'response_status' => ($response['responseStatus']['code'] == 200) ? 1 : 0,
        ];
        $entity = $repository->updateEntity($arrResponse, $entity);

        if($entity->response_status == 0){
            return $this->error(202, (isset($response['responseStatus']['response']) ? $response['responseStatus']['response'] : $response['responseStatus']['messageDetails']));
        }

        $arrData = [
            'amount' => (float)$data['amount'],
            'before' => $response['responseDetails']['banlance'],
            'outstanding' => $response['responseDetails']['playing'],
            'after' => $response['responseDetails']['afterbanlance'],
        ];

        return $this->success(0, $arrData, 'Transfer Success.');

    }

    public function checkBalance($data){

        $validator = Validator::make($data, [
            'username' => 'required',
        ]);

        if($validator->fails()){
            return $this->error(201, $validator->errors());
        }

        // Check Username
//        $username = Username::where('username', $data['username'])->first();
//        if(!$username){
//            return $this->error(201, [], 'Username doesn\'t have.');
//        }

        $arrTrans = [
            'action' => 'transfer',
            'orderid' => 'agent_transfer',
            'custid' => $data['username'],
            'type' => 'detail',
            'amount' => 0,
            'staffid' => 1,
            'from' => 'api_agent_transfer',
            'stateid' => 0,
            'local_ip' => get_client_ip(),
            'auto' => true,
        ];

        $trans = new TransferApiController();
        $response = $trans->transfer($arrTrans);

        if($response['responseStatus']['code'] != 200){
            return $this->error(201, [], $response['responseStatus']['messageDetails']);
        }

        $arrData = [
            'banlance' => $response['responseDetails']['banlance'],
            'outstanding' => $response['responseDetails']['playing'],
            'netbanlance' => $response['responseDetails']['netbanlance'],
        ];

        return $this->success(0, $arrData);

    }

    public function testBalance($data){

        $validator = Validator::make($data, [
            'username' => 'required',
        ]);

        if($validator->fails()){
            return $this->error(201, $validator->errors());
        }

        $username = Username::where('username', $data['username'])
            ->with(['usernameBoard' => function($query){
                $query->select('*');
            }, 'usernameBoard.boardsGame' => function($query){
                $query->select('*');
            }])
            ->first();
        if(!$username){
            return $this->error(201, [], 'Username doesn\'t have.');
        }

        $username = $username->toArray();

        $game_code = $username['username_board']['boards_game']['code'];
        $key = json_decode($username['username_board']['api_code'], true);
        if($game_code == 'csh'){

            $api = new MCSH();
            $api->setKey($key);

            $arrUsername = [
                'id' => $username['ref_id']
            ];
            $res = $api->actionPost($arrUsername, 'balance');

            if($res['status'] != "success"){
                return $this->error(201, [], 'Cannot get balance.');
            }

            $arrData = [
                'banlance' => $res['balance'],
                'outstanding' => 0,
                'netbanlance' => $res['balance'],
            ];

            return $this->success(0, $arrData);

        }

        return $username;
    }

    public function login($data){

        $validator = Validator::make($data, [
            'username' => 'required',
        ]);

        if($validator->fails()){
            return $this->error(201, $validator->errors());
        }

        if(isset($data['pcode'])){
            $game = Games::where('code', $data['pcode'])->first();
            if(!$game){
                return $this->error(201, [], 'pcode invalid.');
            }

            $username = Username::where('core_username.username', $data['username'])
                ->join('core_boards' , 'core_boards.id', '=', 'core_username.board_id')
                ->where('core_boards.game_id', $game->id)
                ->with(['usernameBoard' => function($query){
                    $query->select('*');
                }, 'usernameBoard.boardsGame' => function($query){
                    $query->select('*');
                }])
                ->first();
            if(!$username){
                return $this->error(201, [], 'Username doesn\'t have.');
            }
        }else{
            $username = Username::where('username', $data['username'])
                ->with(['usernameBoard' => function($query){
                    $query->select('*');
                }, 'usernameBoard.boardsGame' => function($query){
                    $query->select('*');
                }])
                ->first();
            if(!$username){
                return $this->error(201, [], 'Username doesn\'t have.');
            }
        }

        $username = $username->toArray();

        if($username['username_board']['boards_game']['is_maintenance']){
            $m_notes = (!empty($username['username_board']['boards_game']['maintenance_notes'])) ? $username['username_board']['boards_game']['maintenance_notes'] : 'ขณะนี้ระบบกำลังอยู่ระหว่างปิดปรับปรุงค่ะ.';
            return $this->error(201, [], $m_notes);
        }

        $m = new MembersApiController();

        // return $login = $m->usernameLogin($data, $username, true);
        $login = $m->usernameLogin($data, $username);

        if(!$login['status']){
            return $this->error(201, $login['message'], 'Login not success!');
        }

        $arrData = [
            'playUrl' => $login['data']['playUrl'],
            'playUrlMobile' => $login['data']['playUrlMobile']
        ];

        return $this->success(0, $arrData, 'Login Success.');

    }

    public function loginTest($data){

        $validator = Validator::make($data, [
            'username' => 'required',
            'pcode' => 'required',
        ]);

        if($validator->fails()){
            return $this->error(201, $validator->errors());
        }

        $game = Games::where('code', $data['pcode'])->first();
        if(!$game){
            return $this->error(201, [], 'pcode invalid.');
        }

        $username = Username::where('core_username.username', $data['username'])
            ->join('core_boards' , 'core_boards.id', '=', 'core_username.board_id')
            ->where('core_boards.game_id', $game->id)
            ->with(['usernameBoard' => function($query){
                $query->select('*');
            }, 'usernameBoard.boardsGame' => function($query){
                $query->select('*');
            }])
            ->first();
        if(!$username){
            return $this->error(201, [], 'Username doesn\'t have.');
        }

        $username = $username->toArray();

        if($username['username_board']['boards_game']['is_maintenance']){
            $m_notes = (!empty($username['username_board']['boards_game']['maintenance_notes'])) ? $username['username_board']['boards_game']['maintenance_notes'] : 'ขณะนี้ระบบกำลังอยู่ระหว่างปิดปรับปรุงค่ะ.';
            return $this->error(201, [], $m_notes);
        }

        $m = new MembersApiController();

        // return $login = $m->usernameLogin($data, $username, true);
        $login = $m->usernameLogin($data, $username);

        if(!$login['status']){
            return $this->error(201, $login['message'], 'Login not success!');
        }

        $arrData = [
            'playUrl' => $login['data']['playUrl'],
            'playUrlMobile' => $login['data']['playUrlMobile']
        ];

        return $this->success(0, $arrData, 'Login Success.');

    }

    public function changePassword($data){

        $validator = Validator::make($data, [
            'username' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return $this->error(201, $validator->errors());
        }

        $username = Username::where('username', $data['username'])
            ->with(['usernameBoard' => function($query){
                $query->select('*');
            }, 'usernameBoard.boardsGame' => function($query){
                $query->select('*');
            }])
            ->first();
        if(!$username){
            return $this->error(201, [], 'Username doesn\'t have.');
        }

        $username = $username->toArray();

        if($username['username_board']['boards_game']['is_maintenance']){
            $m_notes = (!empty($username['username_board']['boards_game']['maintenance_notes'])) ? $username['username_board']['boards_game']['maintenance_notes'] : 'ขณะนี้ระบบกำลังอยู่ระหว่างปิดปรับปรุงค่ะ.';
            return $this->error(201, [], $m_notes);
        }

        $m = new MembersApiController();

        // return $login = $m->changePassword($data, $username, true);
        $login = $m->changePassword($data, $username);

        if(!$login['status']){
            return $this->error(201, $login['message'], 'Not success!');
        }

        return $this->success(0, [], 'Change Password Success.');

    }

    public function getWinloss($data){

        $validator = Validator::make($data, [
            'agent' => 'required',
            'pcode' => 'required',
            'sDate' => 'required',
            'eDate' => 'required',
        ]);

        if($validator->fails()){
            return $this->error(201, $validator->errors());
        }

        $game = Games::where('code', $data['pcode'])
            ->with(['boardsGame' => function($query) use ($data){
                //$query->where('agent_id', $this->agent_id)->where('ref', $data['agent'])->where('is_active', 1)->select('*');
                $query->where('ref', $data['agent'])->where('is_active', 1)->select('*');
            }])
            ->first();

        if(!$game){
            return $this->error(201, [], 'The pcode invalid');
        }
        $game = $game->toArray();
        if(empty($game['boards_game'])){
            return $this->error(201, [], 'This game not available for this agent.');
        }

        $board = $game['boards_game'][0];
        $game_code = $game['code'];
        $api_code = $board['api_code'];

        if($game_code == 'csh'){

            $api = new CSH();
            $key = json_decode($api_code, true);
            $api->setKey($key);

            $validator = Validator::make($data, [
                'month' => 'required',
                'lastedId' => 'required',
            ]);

            if($validator->fails()){
                return $this->error(201, $validator->errors());
            }

            $items = $api->getBetItems($data['lastedId'], $data['month']);
            if($items['status'] != 'success'){
                return $this->error(302, $items['msg']);
            }
            return $this->success(0, $items['data']);

        }
        else{

            return $this->error(201, [], 'Game does not support!');

        }

    }

}
