<?php

namespace App\Http\Controllers\Games\Dg;

use Illuminate\Http\Request;
use Modules\Core\Boards\Entities\Boards;
use Modules\Core\Username\Entities\Username;
use Modules\Member\Members\Entities\Members;

class MemberController extends DGController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function getUser($username)
    {
        $setParam = [
            "member" => [
                'username' => $username
            ],
        ];
        $this->setParam($setParam, 'user/getBalance');
        $response = $this->push();

        return $response;
    }

    /**
     * Member Registration default allocation is limit Group A.
     * Registered members can not be modified after successful registration.
     * WinLimit the amount a member could win perday , 0 for unlimited.
     * Creating Member account not less then 4digits and starts with an alphabet,max 30character. "[a-zA-Z0-9_]{3,30}"
     * Please login using MD5 encryption, server will directly save password into the database.
     * During testing period, only registered members with testing credits can test and thereafter not permitted into games after period of testing.
     */
    public function create(Request $request)
    {
        $data = $request->all();

        $url = $this->apiurl."user/signup/".$this->agentuser."/";

        $response = $this->pushAPI($url, $data);

        print_r(json_decode($response));
    }

    /**
     * 1.The returned token is a valid token only if codeId = 0
     * 2.open game's url,such as:
     * PC Browser Open Game: list[0] + token
     * Mobile Open Game: list[1] + token + &language=lang
     */
    public function login(Request $request)
    {
        $data = $request->all();

        $url = $this->apiurl."user/login/".$this->agentuser."/";

        $response = $this->pushAPI($url, $data);

        print_r(json_decode($response));
    }

    public function demo(Request $request)
    {
        $data = $request->all();

        $url = $this->apiurl."user/free/".$this->agentuser."/";

        $response = $this->pushAPI($url, $data);

        print_r(json_decode($response));
    }

    public function balance(Request $request)
    {
        $data = $request->all();

        $url = $this->apiurl."user/getBalance/".$this->agentuser."/";

        $response = $this->pushAPI($url, $data);

        print_r(json_decode($response));
    }

    public function balanceupdate(Request $request)
    {
        $data = $request->all();

        $arrData = array(
            'data' => str_random(20)
        );

        $arrMerge = array_merge($arrData, $data);

        $url = $this->apiurl."account/transfer/".$this->agentuser."/";

        $response = $this->pushAPI($url, $arrMerge);

        print_r(json_decode($response));
    }

    public function lockByBoard($id){

        $board = Boards::where('id', $id)->first();
        // return $board;
        $key = json_decode($board['api_code'], true);
        $this->setKey($key);

        $users = Username::where('board_id', $id)->whereNotNull('is_created_at')->get();

        $response = [];
        foreach ($users as $user) {
            $setParam = [
                "member" => [
                    'username' => $user['username'],
                    'status' => 2
                ],
            ];
            $this->setParam($setParam, 'user/update');
            $response[] = $this->push();
            // $response[] = json_decode($res, true);
        }

        return $response;

    }

}
