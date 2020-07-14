<?php

namespace App\Http\Controllers\Games\Lotto;

use App\Http\Controllers\AppController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LottoController extends AppController
{

    public $apiUrl;
    public $gameUrl;
    public $key;
    public $agent;
    protected $sandbox = false;

    const ENV_APP = [
        'test' => [
            'api_url' => '',
            'game_url' => '',
            'key' => '',
            'agent' => '',
            'redirect' => '',
        ],
        'production' => [
            'api_url' => '',
            'game_url' => '',
            'key' => '',
            'agent' => '',
            'redirect' => '',
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
    public $redirect;

    public $ch;

    const BET_LIMIT = [];
    const BET_LIMIT_TYPE = 'multi';
    const CODE = [];

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
        $this->redirect = $mode['redirect'];

        if(!empty($key)) { // If key not default
            $this->setKey($key);
        }
    }

    public function setKey($key){

        $this->apiUrl = $key['api_url'];
        $this->redirect = $key['redirect'];
        $this->agent = $key['agent'];

    }

    public function getCode($id){
        return self::CODE[$id];
    }

    public function setParam($data, $action)
    {
        $arrData = [];

        $data_request = array_merge($arrData, $data);

        $data_json = $data_request;
        $this->data = $data_json;

        $url = $this->apiUrl.$action;
        $this->url = $url;

        $encode = http_build_query($data_json);

        return compact('url', 'data', 'data_request', 'encode');
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
    public function push($post = true, $gzip = false)
    {
        $data = $this->data;
        $response = $this->pushAPI($data, $post, $gzip);

        return $response;
    }

    public function pushAPI($data, $post = true, $gzip = false){

        $data_post = http_build_query($data);

        $header = array(
            "Content-Type: application/x-www-form-urlencoded"
        );
        $this->ch = curl_init();
        if($post) {
            curl_setopt($this->ch, CURLOPT_URL, $this->url);
            curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data_post);
        }else{
            curl_setopt($this->ch, CURLOPT_URL, $this->url."?".$data_post);
            curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "GET");
        }
        if($gzip){
            curl_setopt($this->ch, CURLOPT_ENCODING , "gzip");
        }
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $header);

        $response = curl_exec($this->ch);
        $err = curl_error($this->ch);

        curl_close($this->ch);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }

    }

    public function getBetItemByUpdateDate()
    {
        $date = date('Y-m-d H:i:s');
        $sDate = date('Y-m-d\TH:i:s', strtotime('55 minutes', strtotime($date)));

        $setParam = [
            "timeFrom" => $sDate."+08:00",
        ];

        $param = $this->setParam($setParam, 'wallet/getTransactionByUpdateDate');

        $response = $this->push(false, true);
        $response = json_decode($response, true);

        return $response;
    }

    public function getBetItemByTxTime()
    {
        $date = date('Y-m-d H:i:s');
        $sDate = date('Y-m-d H:i:s', strtotime('50 minutes', strtotime($date)));
        $eDate = date('Y-m-d H:i:s', strtotime('60 minutes', strtotime($date)));

        $setParam = [
            "startTime" => "2020-01-10T12:00:00+08:00",
            "endTime" => "2020-01-10T12:00:00+08:00",
        ];

        $param = $this->setParam($setParam, 'wallet/getTransactionByTxTime');

        $response = $this->push(false, true);
        $response = json_decode($response, true);

        return $response;
    }
}
