<?php

namespace App\Http\Controllers\Games\Tfg;

use App\Http\Controllers\AppController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TfgController extends AppController
{

    public $apiUrl;
    public $apiReportUrl;
    public $gameUrl;
    public $key;
    public $authcode;
    public $agent;
    public $operator_id = 52;
    protected $sandbox = false;

    const ENV_APP = [
        'test' => [
            'api_url' => 'https://spi-test.r4espt.com/api/v2/',
            'api_url2' => '',
            'report_api' => '',
            'game_url' => 'https://gc.zly889.com/launch.html?auth=b0782d583ff9433196ae619e2218d8f2&token=',
            'key' => '',
            'authcode' => '',
            'agent' => '',
        ],
        'production' => [
            'api_url' => 'https://spi.r4espt.com/api/v2/',
            'api_url2' => '',
            'report_api' => '',
            'game_url' => 'https://gc.zly889.com/launch.html?auth=f8067d748f744df3a72213e03c1b764c&token=',
            'key' => '',
            'authcode' => '',
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

    ];

    const STATUS_WAIT = ['waiting', 'running'];
    const STATUS_COMPLETE = ['won', 'lose', 'half won', 'half lose', 'draw', 'reject', 'refund', 'void'];
    const STATUS_CANCEL = ['reject', 'refund', 'void'];
    const STATUS_COMM = ['won', 'lose', 'half won', 'half lose', 'draw'];

    public function __construct($key = null)
    {
        //
        if($this->sandbox){
            $mode = self::ENV_APP['test'];
        }else{
            $mode = self::ENV_APP['production'];
        }

        $this->key = $mode['key'];
        $this->authcode = $mode['authcode'];
        $this->agent = $mode['agent'];
        $this->apiUrl = $mode['api_url'];
        $this->apiUrl2 = $mode['api_url2'];
        $this->apiReportUrl = $mode['report_api'];
        $this->gameUrl = $mode['game_url'];

        if(!empty($key)) { // If key not default
            $this->setKey($key);
        }
    }

    public function setKey($key){
        $this->key = $key['key'];
    }

    public function getCode($id){
        return self::CODE[$id];
    }

    public function setParam($data, $action, $prefix = null, $suffix = null, $url = null)
    {

        $time = current_millis();
        $arrData = [];

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
        $key = $this->key;

        $param = http_build_query($data_json);
        $json = json_encode($data_json);
        $urlFull = $url."?".$param;
        // $param = urldecode($param);

        return compact('url', 'urlFull', 'data', 'data_request', 'param', 'key', 'json');
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

        $header = array(
            "Content-Type: application/json",
            "Authorization: Token ".$this->key
        );
        $this->ch = curl_init();
        if($post) {
            $data_post = json_encode($data, JSON_UNESCAPED_UNICODE);
            curl_setopt($this->ch, CURLOPT_URL, $this->url);
            curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data_post);
        }else{
            $data_post = http_build_query($data);
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
        $err = curl_error($this->ch);

        curl_close($this->ch);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }

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
