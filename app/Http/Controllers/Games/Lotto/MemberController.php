<?php

namespace App\Http\Controllers\Games\Lotto;

use Illuminate\Http\Request;

class MemberController extends LottoController
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
            "agent" => $this->agent,
        ];

        $param = $this->setParam($setParam, 'register');

        $response = $this->push();
        $response = json_decode($response, true);

        return $response;
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
