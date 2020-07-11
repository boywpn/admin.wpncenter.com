<?php

namespace App\Http\Controllers\Games\Sexy;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class MemberController extends SexyController
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

    public function create(Request $request)
    {
        $data = $request->input();

        return $this->saveUsername($data);
    }

    public function saveUsername($data = null)
    {
        $betLimit = [
            'SEXYBCRT' => [
                'LIVE' => [
                    'limitId' => [
                        260901
                    ]
                ]
            ]
        ];

        $setParam = [
            "userId" => $data['username'],
            "currency" => "THB",
            "betLimit" => json_encode($betLimit) // 10-2000
        ];

        $param = $this->setParam($setParam, 'wallet/createMember');

        $response = $this->push(false);
        $response = json_decode($response, true);

        return $response;
    }

    public function login($username)
    {
        $setParam = [
            'userId' => $username,
            'gameType' => 'LIVE',
            'platform' => 'SEXYBCRT',
            'gameCode' => 'MX-LIVE-002',
        ];

        $response = $this->actionGet($setParam, 'wallet/doLoginAndLaunchGame');

        return  Redirect::to($response['url']);

        // return $response;
    }

    public function actionPost($data, $url)
    {

        $param = $this->setParam($data, $url);

        $response = $this->push();
        $response = json_decode($response, true);

        return $response;
    }

    public function actionGet($data, $url, $debug = false)
    {

        $param = $this->setParam($data, $url);

        $response = $this->push(false);
        $response = json_decode($response, true);

        if($debug){
            return compact('param', 'response');
        }

        return $response;
    }

}
