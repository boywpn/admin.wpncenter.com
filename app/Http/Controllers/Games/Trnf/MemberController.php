<?php

namespace App\Http\Controllers\Games\Trnf;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Modules\Api\Traits\RespondTrait;
use Modules\Core\Boards\Entities\Boards;
use Modules\Core\Username\Entities\Username;
use Modules\Report\Commission\Entities\Commission;

class MemberController extends TrnfController
{

    use RespondTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function create(Request $request)
    {
        $data = $request->input();

        return $this->saveUsername($data);
    }

    public function saveUsername($data = null)
    {
        $setParam = [
            'type' => 'register',
            'custid' => $data['username'],
            'username_login' => $data['username_login'],
            'password' => $data['password']
        ];

        if($data['game'] == 'ufa'){
            $this->apiUrl = $this->apiUrlJp;
        }

        $param = $this->setParam($setParam, $data['game'].'/');

        $response = $this->push();
        $response = json_decode($response, true);

        return $response;
    }

    public function actionPost($data, $url, $debug = false)
    {

        $param = $this->setParam($data, $url);

        $response = $this->push();
        $response = json_decode($response, true);

        if($debug) {
            return compact('param', 'response');
        }

        return $response;
    }

    public function actionGet($data, $url, $prefix = null, $suffix = null, $debug = false)
    {

        $param = $this->setParam($data, $url, $prefix, $suffix);

        $response = $this->push(false);
        $response = json_decode($response, true);

        if($debug) {
            return compact('param', 'response');
        }

        return $response;
    }

    public function getLogin($data){

        // For Sbo
        $setParam = [
            'Username' => $data['user_game'],
            'Portfolio' => $data['game_type'],
        ];

        $res = $this->actionPost($setParam, 'web-root/restricted/player/login.aspx');

        if(empty($res)){
            return response()->json(['status' => false, 'msg' => 'ระบบกำลังปรับปรุง...']);
        }

        if($res['error']['id'] != 0){
            return $res;
        }

        $res['url'] = "https:".$res['url'];

        $res['playUrl'] = $res['url'];
        $res['playUrlMobile'] = $res['url'];

        if($data['game_type'] == "SportsBook") {
            $res['playUrl'] = $res['url'] . "&lang=th-th&oddstyle=MY&theme=sbo&oddsmode=Single&device=d";
            $res['playUrlMobile'] = $res['url'] . "&lang=th-th&oddstyle=MY&theme=sbo&oddsmode=Single&device=m";
        }

        if($data['game_type'] == "Casino"){
            $loginMode = (isset($data['login_mode'])) ? $data['login_mode'] : 2;
            $res['playUrl'] = $res['url'] . "&locale=th-th&device=d&loginMode=".$loginMode."&productId=".$data['game_id'];
            $res['playUrlMobile'] = $res['url'] . "&locale=th-th&device=m&loginMode=".$loginMode."&productId=".$data['game_id'];
        }

        if($data['game_type'] == "Games"){
            $res['playUrl'] = $res['url'] . "&gameId=".$data['game_id'];
            $res['playUrlMobile'] = $res['url'] . "&gameId=".$data['game_id'];
        }

        if($data['game_type'] == "SeamlessGame"){
            $res['playUrl'] = $res['url'] . "&gpid=".$data['gpid']."&gameid=".$data['game_id']."&lang=th-th&device=d";
            $res['playUrlMobile'] = $res['url'] . "&gpid=".$data['gpid']."&gameid=".$data['game_id']."&lang=th-th&device=m";
        }

        return $res;

    }

    public function login(Request $request){

        $data = $request->all();

        return $this->getLogin($data);

    }

    public function logout(Request $request){

        $data = $request->all();

        // For Sbo
        $setParam = [
            'Username' => $data['user_game'],
        ];

        $res = $this->actionPost($setParam, 'web-root/restricted/player/logout.aspx');

        if($res['error']['id'] != 0){
            return $res;
        }

        return $res;

    }

    public function balance(Request $request){

        $data = $request->all();

        // For Sbo
        $setParam = [
            'Username' => $data['user_game'],
        ];

        $res = $this->actionPost($setParam, 'web-root/restricted/player/get-player-balance.aspx');

        if($res['error']['id'] != 0){
            return $res;
        }

        return $res;

    }

    public function getBetlimit(Request $request){

        $data = $request->all();

        $validator = Validator::make($data, [
            'Username' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['status' => false, 'msg' => $validator->errors()]);
        }

        return $limit = $this->betlimit($data, 'get');

    }

    public function setBetlimit(Request $request){

        $data = $request->all();

        $validator = Validator::make($data, [
            'Username' => 'required',
            'Min' => 'required',
            'Max' => 'required',
            'MaxPerMatch' => 'required',
            'CasinoTableLimit' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['status' => false, 'msg' => $validator->errors()]);
        }

        return $limit = $this->betlimit($data, 'set');

    }

    public function betlimit($data, $type = 'get'){

        // Get Username
        $username = Username::where('username', $data['Username'])->first();
        if(!$username){
            return response()->json(['status' => false, 'msg' => 'Username doesn\'t exist!']);
        }

        $boards = Boards::getBoardByID($username->board_id);
        $key = json_decode($boards['api_code'], true);

        $this->key = $key['key'];
        $this->agent = $key['agent'];

        // For Sbo
        if($type == 'get') {
            $setParam = [
                'Username' => $data['Username']
            ];
            $res = $this->actionPost($setParam, 'web-root/restricted/player/get-member-bet-settings-with-sportid-and-markettype.aspx');
        }else{
            $setParam = [
                'Username' => $data['Username'],
                'Min' => $data['Min'],
                'Max' => $data['Max'],
                'MaxPerMatch' => $data['MaxPerMatch'],
                'CasinoTableLimit' => $data['CasinoTableLimit']
            ];
            return $res = $this->actionPost($setParam, 'web-root/restricted/player/update-player-bet-settings.aspx', true);
        }


        if($res['error']['id'] != 0){
            return $res;
        }

        return $res;

    }

    public function getHistoryCommission(Request $request){

        $data = $request->all();

        $user = Username::where('username', $data['user_game'])
            ->select('id', 'username')
            ->first();

        if(!$user){
            return response()->json(['status' => false, 'msg' => 'ไม่มีข้อมูลของ Username นี้ในระบบ']);
        }

        $comms = Commission::where('username_id', $user->id)
            ->where('date', $data['date'])
            ->with(['commResults' => function($q){
                $q->select('*')->orderBy('id', 'desc');
            }])
            ->orderBy('id', 'desc')
            ->get();

        if(!$comms){
            return response()->json(['status' => false, 'msg' => 'ไม่มีข้อมูลค่าคอม Username นี้ในระบบ']);
        }

        $arrComm = [];
        foreach ($comms as $comm){
            $comm = $comm->toArray();

            $match = (isset($comm['comm_results'][0])) ? json_decode($comm['comm_results'][0]['game_result'], true) : null;

            $arrComm[] = [
                'detail' => [
                    'betlist_id' => $comm['betlist_id'],
                    'username' => $comm['username'],
                    'date' => $comm['date'],
                    'pay_time' => $comm['pay_time'],
                    'comm_value' => $comm['comm_value'],
                    'amount' => $comm['amount']
                ],
                'match' => $match
            ];
        }

        return response()->json(['status' => true, 'msg' => 'Success', 'data' => $arrComm]);

    }

}
