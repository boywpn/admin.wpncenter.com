<?php

namespace App\Http\Controllers\Games\Sa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class MemberController extends SAController
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

    public function create()
    {
        $data = [
            "q" => $this->genQS(),
            "s" => $this->genMD5()
        ];

        $response = $this->pushAPI($data);
    }

    public function status()
    {
        $data = [
            "q" => $this->genQS(),
            "s" => $this->genMD5()
        ];

        $response = $this->pushAPI($data);
    }

    public function betLimit($board){

        return $board;

    }

    public function login($username, $table){

        $setParam = [
            'method' => 'LoginRequest',
            'Username' => $username,
            'CurrencyType' => 'THB',
            'Lang' => 'th',
        ];
        $this->setParam($setParam);
        $response = $this->push();
        $res = xmlDecode($response, true);

        $option = 'options=defaulttable='.$table.',hideslot=1';

        $url = 'username='.$username.'&token='.$res['Token'].'&lobby='.$this->lobby.'&lang=en_US&'.$option.'&h5web=true&returnurl=';
        $url = $this->clienturl."?".$url;

        return  Redirect::to($url);

    }
}
