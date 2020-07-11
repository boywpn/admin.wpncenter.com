<?php

namespace App\Http\Controllers\Games\Ibc;

use Illuminate\Http\Request;

class MemberController extends IbcController
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
            "username" => $data['username'],
        ];

        $param = $this->setParam($setParam, 'createMember.aspx');

        $response = $this->push(false);
        $response = json_decode($response, true);

        // Set getBalance
        if($response['errCode'] == 0 || $response['errCode'] == 82){

            $setParamBal = [
                'password' => $data['password'],
                'providercode' => 'IB',
                'username' => $data['username']
            ];
            $response = $this->actionGet($setParamBal, 'getBalance.aspx');

        }

        // return compact('param', 'response', 'setParamBal', 'responseBal');

        return $response;
    }

    public function actionPost($data, $url)
    {

        $param = $this->setParam($data, $url);

        $response = $this->push();
        $response = json_decode($response, true);

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

}
