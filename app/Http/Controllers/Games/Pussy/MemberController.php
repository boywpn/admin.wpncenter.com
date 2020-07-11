<?php

namespace App\Http\Controllers\Games\Pussy;

use Illuminate\Http\Request;

class MemberController extends PussyController
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

    public function randomUsername($data = null)
    {

        $setParam = [
            "action" => "RandomUserName",
            "userName" => $data['agent'],
            "UserAreaId" => 2, // Thailand
        ];
        $param = $this->setParam($setParam, 'ashx/account/account.ashx');
        $response = $this->push(false);
        $response = json_decode($response, true);

        return $response;
    }

    public function addUser($data = null)
    {
        $setParam = [
            "action" => "addUser",
            "agent" => $data['agent'],
            "userName" => $data['account'],
            "PassWd" => $data['password'],
            "Name" => $data['username'],
            "Tel" => "",
            "Memo" => "",
            "pwdtype" => 1,
            "UserType" => 1,
            "UserAreaId" => 2, // Thailand
        ];
        $this->setParam($setParam, 'ashx/account/account.ashx');
        $response = $this->push(false);
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

}
