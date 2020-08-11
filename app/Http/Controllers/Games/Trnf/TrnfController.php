<?php

namespace App\Http\Controllers\Games\Trnf;

use App\Http\Controllers\AppController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TrnfController extends AppController
{

    public $apiUrl;
    public $apiUrlJp;
    public $apiReportUrl;
    public $gameUrl;
    public $key;
    public $agent;
    protected $sandbox = false;

    const ENV_APP = [
        'test' => [
            'api_url' => '',
            'api_url_jp' => '',
            'report_api' => '',
            'game_url' => '',
            'key' => '',
            'agent' => '',
        ],
        'production' => [
            'api_url' => 'https://trnf.tk/api/transfer/',
            'api_url_jp' => 'https://jp.trnf.tk/api/transfer/',
            'report_api' => '',
            'game_url' => '',
            'key' => 'JTOk1zjWmobuLuF8rz31KoOOZZrXOSZ9oywW4p6jbGg=',
            'agent' => 'api',
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
    public $ag_username;
    public $ag_password;
    public $ag_level;
    public $ag_code;

    public $ch;

    const BET_LIMIT = [];
    const BET_LIMIT_TYPE = 'multi';
    const CODE = [

    ];

    const STATUS_WAIT = [];
    const STATUS_COMPLETE = [];
    const STATUS_CANCEL = [];
    const STATUS_COMM = [];

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
        $this->apiUrlJp = $mode['api_url_jp'];
        $this->apiReportUrl = $mode['report_api'];
        $this->gameUrl = $mode['game_url'];

        if(!empty($key)) { // If key not default
            $this->setKey($key);
        }
    }

    public function setKey($key){
        $this->agent = $key['agent'];
        $this->key = $key['key'];
    }

    public function setAgentUser($data){
        $this->ag_username = $data['username'];
        $this->ag_password = $data['password'];
        $this->ag_level = $data['level'];
        $this->ag_code = $data['code'];
    }

    public function getCode($id){
        return self::CODE[$id];
    }

    public function setParam($data, $action, $prefix = null, $suffix = null, $url = null)
    {

        if($data['game'] == 'ufa'){
            $this->apiUrl = $this->apiUrlJp;
        }

        $arrData = array(
            'username' => $this->agent,
            'apikey' => $this->key,

            'staffid' => (!empty($data['staffid'])) ? $data['staffid'] : 1,
            'amount' => (!empty($data['amount'])) ? $data['amount'] : null,
            'orderid' => (!empty($data['orderid'])) ? $data['orderid'] : null,
            'stateid' => (!empty($data['stateid'])) ? $data['stateid'] : null,
            'from' => (!empty($data['from'])) ? $data['from'] : 'admin',

            'ag_username' => $this->ag_username,
            'ag_password' => $this->ag_password,
            'ag_level' => (!empty($this->ag_level)) ? $this->ag_level : "_Age_Sub",
            'ag_code' => $this->ag_code,
        );

        if(!empty($prefix)){
            $arrData = array_merge($prefix, $arrData);;
        }

        $data_request = array_merge($arrData, $data);

        if(!empty($suffix)){
            $data_request = array_merge($data_request, $suffix);;
        }

        $data_json = $data_request;
        $this->data = $data_json;

        if(!empty($url)){
            $this->apiUrl = $url;
        }

        $url = $this->apiUrl.$action;
        $this->url = $url;

        $param = http_build_query($data_json);
        $urlFull = $url."?".$param;

        return compact('url', 'urlFull', 'data', 'data_request', 'param');
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
    public function push($post = true)
    {
        $data = $this->data;
        $response = $this->pushAPI($data, $post);

        return $response;
    }

    public function pushAPI($data, $post = true){

//        $data_post = json_encode($data, JSON_UNESCAPED_UNICODE);
//        $data_post = http_build_query($data);
//        $data_post = urldecode($data_post);
        $data_post = $data;

        $header = array(
            "Accept: application/json",
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
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($this->ch);
        $response = preg_replace('/[\xEF\xBB\xBF]/', '', $response); // Remove BOM
        $err = curl_error($this->ch);

        curl_close($this->ch);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }

    }
}
