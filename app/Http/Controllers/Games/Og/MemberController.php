<?php

namespace App\Http\Controllers\Games\Og;

use Illuminate\Http\Request;

class MemberController extends OGController
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

    // function for all request !!! important
    public function push($post = true)
    {
        $response = $this->pushAPI($post);

        return $response;
    }

    public function create(Request $request)
    {
        $data = $request->all();

        $url = $this->apiurl."register";

        $response = $this->pushAPI($url, $data);

        print_r(json_decode($response, true));
    }

    public function player(Request $request)
    {
        $data = $request->all();
        $data_post = http_build_query($data);

        $url = $this->apiurl."players?".$data_post;

        $response = $this->pushAPI($url, $data, false);

        print_r(json_decode($response, true));
    }

    public function balance(Request $request)
    {
        $data = $request->all();
        $data_post = http_build_query($data);

        $url = $this->apiurl."game-providers/1/balance?".$data_post;

        $response = $this->pushAPI($url, $data, false);

        print_r(json_decode($response, true));
    }

    public function balanceupdate(Request $request)
    {
        $data = $request->all();

        $url = $this->apiurl."game-providers/1/balance";

        $response = $this->pushAPI($url, $data);

        print_r(json_decode($response, true));
    }

    public function gamekey($data)
    {
        $data_post = http_build_query($data);

        $url = $this->apiurl."game-providers/1/games/oglive/key?".$data_post;

        $response = $this->pushAPI($url, $data, false);

        return json_decode($response, true);
    }

    public function login(Request $request)
    {
        $data = $request->all();

        // get game key
        $key = $this->gamekey($data);
        if($key['status'] == "error"){
            return $key;
        }

        $url = $this->apiurl."game-providers/1/play?key=".$key['data']['key'];

        $response = $this->pushAPI($url, $data, false);

        print_r(json_decode($response, true));
    }

    public function transaction(Request $request)
    {
        $data = $request->all();

        $url = $this->apifetchurl."Transaction";

        $response = $this->pushFetchAPI($url, $data);

        print_r(json_decode($response, true));
    }

}
