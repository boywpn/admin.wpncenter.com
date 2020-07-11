<?php

namespace App\Http\Controllers\Games\Aec;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AecController extends Controller
{

    public $apiUrl;
    public $gameUrl;
    public $key;
    public $agent;
    protected $sandbox = false;

    const ENV_APP = [
        'test' => [
            'api_url' => 'https://www.api-aec.com/',
            'game_url' => 'https://www.api-aec.com/',
            'key' => 'e06dcae665043dffe06dcae665043dffe06dcae665043dffe06dcae665043dffe06dcae665043dff1b624e59fdbd6bc410ea507cf0fa4c094342a56ac0df47337481339e1fadfa2d',
            'agent' => 'TAaatest',
        ],
        'production' => [
            'api_url' => 'https://www.api-aec.com/',
            'game_url' => 'https://www.api-aec.com/',
            'key' => 'e06dcae665043dffe06dcae665043dffe06dcae665043dffe06dcae665043dffe06dcae665043dff1b624e59fdbd6bc410ea507cf0fa4c094342a56ac0df47337481339e1fadfa2d',
            'agent' => '',
        ],
    ];

    public $dir;
    public $token_file;
    public $token;
    public $time;
    public $url;
    public $qs;
    public $md5;
    public $data;

    public $ch;

    const BET_LIMIT = [];
    const BET_LIMIT_TYPE = 'multi';
    const CODE = [
        '0'=>'Operation Successful',
        '1'=>'Have Some Error',
    ];

    public function __construct($key = null)
    {
        //
        if($this->sandbox){
            $mode = self::ENV_APP['test'];
        }else{
            $mode = self::ENV_APP['production'];
        }

        $this->key = $mode['key'];
        $this->agent = $mode['agent'];
        $this->apiUrl = $mode['api_url'];
        $this->gameUrl = $mode['game_url'];

        if(!empty($key)) { // If key not default
            $this->key = $key['key'];
            $this->agent = $key['agent'];
        }
    }

    public function getCode($id){
        return self::CODE[$id];
    }

    public function setParam($data, $action)
    {
        $arrData = array(
            'companyKey' => $this->key,
        );

        $data_request = array_merge($arrData, $data);

        $data_json = json_encode($data_request);
        $this->data = $data_json;

        $url = $this->apiUrl.$action;
        $this->url = $url;

        return compact('url', 'data', 'data_request');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return response()->json(['status' => true, 'codeid' => 0, 'message' => 'Access Success!'], 200);
    }

    /**
     * function for all request !!! important
    */
    public function push()
    {
        $data = $this->data;
        $response = $this->pushAPI($data);

        return $response;
    }

    public function pushAPI($data, $post = true){

        $data_post = $data;

        $header = array(
            "Content-Type: application/json",
        );
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $this->url);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data_post);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($this->ch);
        $err = curl_error($this->ch);

        curl_close($this->ch);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }

    }

    public function getUser($username)
    {
        $setParam = [
            "username" => $username,
        ];
        $this->setParam($setParam, 'player/get-player-balance.aspx');
        $response = $this->push();

        $response = json_decode($response, true);

        return $response;
    }

     public function getBetItem($agentName, $gameType, $sDate, $eDate, $debug = false)
     {
         $setParam = [
             'AgentName' => $agentName,
             'Act' => 'RP_GET_CUSTOMER',
             'portfolio' => $gameType,
             'startDate' => $sDate,
             'endDate' => $eDate,
             'lang' => 'TH-TH'
         ];

         $param = $this->setParam($setParam, 'Public/InnoExcData.ashx');
         $response = $this->push();
         $response = json_decode($response, true);

         if($debug){
             return compact('param', 'response');
         }

         return $response;
     }

    // public function setBetLimit($username, $limit)
    // {
    //     $this->time = date("YmdHis");
    //     $arrData = array(
    //         'Key' => $this->secretkey,
    //         'Time' => $this->time,
    //         'method' => 'SetBetLimit',
    //         'Username' => $username,
    //         'Currency' => 'THB',
    //     );

    //     foreach ($limit as $key => $id){
    //         $arrData[$key] = $id;
    //     }

    //     $this->url = http_build_query($arrData);

    //     $response = $this->push();
    //     $res = xmlDecode($response, true);

    //     return $res;
    // }

    // public function getBetLimitList()
    // {
    //     $this->time = date("YmdHis");
    //     $arrData = array(
    //         'Key' => $this->secretkey,
    //         'Time' => $this->time,
    //         'method' => 'QueryBetLimit',
    //         'Currency' => 'THB'
    //     );
    //     $this->url = http_build_query($arrData);
    //     $response = $this->push();
    //     $res = xmlDecode($response, true);

    //     return $res;
    // }
}
