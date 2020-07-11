<?php

namespace App\Http\Controllers\Games\Sbo_old;

use Illuminate\Http\Request;

class MemberController extends SboController
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

        $setParam = [
            "AccountId" => $data['username'],
            "Currency" => "THB",
            "Password" => $data['password']
        ];
        $param = $this->setParam($setParam, ['Password'], 'api/v2/Player/CreatePlayer/'.$this->merchant_code);

        $response = $this->push();
        $response = json_decode($response, true);

        return compact('param', 'response');
    }

    public function login(Request $request)
    {
        $data = $request->input();

        $setPayload = [
            "AccountId" => $data['username'],
            "Currency" => "IDR"
        ];
        $payload = $this->setPayLoad($setPayload);

//        $response = $this->push();
//        $response = json_decode($response, true);

        return compact('payload');
    }

}
