<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\AppController;
use App\Http\Controllers\Games\Csh\MemberController AS CSH;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Modules\Core\Boards\Entities\Boards;
use Modules\Core\Username\Entities\Username;

/**
 *
 * Class ContactsApiController
 *
 * @package Modules\Api\Http\Controllers
 */
class EventsController extends AppController
{

    public function login(Request $request){

        $post = $request->all();

        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['status'=>false, 'msg' => 'ข้อมูลไม่ครบ', 'data' => $validator->errors()]);
        }

        $username = Username::where('ev_username', $post['username'])
            ->where('ev_status', 1)
            ->select('id', 'ref_id', 'board_id', 'username', 'ev_username', 'ev_password')
            ->first();

        if(!$username){
            return response()->json(['status'=>false, 'msg' => 'ไม่มีข้อมูล Username ในระบบ กรุณาติดต่อ Call Center', 'data' => []]);
        }

        $pass = Crypt::decryptString($username->ev_password);

        if($pass != $post['password']){
            return response()->json(['status'=>false, 'msg' => 'Username และ Password ไม่ตรงกัน กรุณาติดต่อ Call Center', 'data' => []]);
        }

        $board = Boards::where('id', $username->board_id)
            ->with(['boardsGame' => function($query){
                $query->select('*');
            }])
            ->select('id', 'name', 'api_code', 'game_id')
            ->first()
            ->toArray();

        $key = json_decode($board['api_code'], true);

        if($board['boards_game']['code'] == "csh") {

            $api = new CSH($key);

            $setParam = [
                'username' => $username['username'],
            ];

            $api->apiUrl = 'https://kmo.psg777.com/api/';
            $res =$api->actionPost($setParam, 'userLogin');

            if($res['status'] != 'success'){
                return response()->json(['status'=>false, 'msg' => $res['msg'], 'data' => []]);
            }

            $res['playUrl'] = $api->gameUrl.$res['login_token'];
            $res['playUrlMobile'] = $api->gameUrl.$res['login_token'];

            // return compact('username', 'board', 'res');
            return $res;

        }

        return response()->json(['status'=>false, 'msg' => 'ไม่มีข้อมูลในระบบ กรุณาติดต่อ Call Center', 'data' => []]);

    }

}