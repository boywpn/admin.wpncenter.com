<?php

namespace App\Http\Controllers\Games\Aec;

use Illuminate\Http\Request;

class MemberController extends AecController
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
        $setParam = [
            "userName" => $data['username'],
            "currencyName" => "THB",
            "agentName" => $this->agent
        ];

        $param = $this->setParam($setParam, 'Public/ckAcc.ashx');
        $response = $this->push();
        $response = json_decode($response, true);

        $response['url'] = null;
        $response['urlMobile'] = null;
        if($response['error'] == 0){
            $response['url'] = $this->gameUrl."Public/validate.aspx?us=".$data['username']."&k=".$response['token']."&device=d&oddsstyle=MY&oddsmode=Single&lang=TH-TH&currencyName=THB&sk=W0AT";
            //$response['urlMobile'] = $this->gameUrl."Public/validate.aspx?us=".$data['username']."&k=".$response['token']."&device=d&oddsstyle=MY&oddsmode=Single&lang=TH-TH&currencyName=THB&sk=W0AT";
            $response['urlMobile'] = $this->gameUrl."Public/validate.aspx?us=".$data['username']."&k=".$response['token']."&device=m&oddsstyle=MY&oddsmode=Single&lang=TH-TH&currencyName=THB&sk=H50AT";
        }

        return $response;
    }

    public function actionPost($data = null, $debug = false)
    {

        $param = $this->setParam($data, 'Public/InnoExcData.ashx');
        $response = $this->push();
        $response = json_decode($response, true);

        if($debug){
            return compact('param', 'response');
        }

        return $response;
    }

    public function suspendUser($user, $status)
    {

        $setParam = [
            'userName' => $user,
            'Act' => 'MB_UPD_STATUS',
            'status' => $status,
        ];

        $param = $this->setParam($setParam, 'Public/InnoExcData.ashx');
        $response = $this->push();
        $response = json_decode($response, true);

        return $response;
    }

}
