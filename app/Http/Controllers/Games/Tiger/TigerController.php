<?php

namespace App\Http\Controllers\Games\Tiger;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TigerController extends Controller
{

    public $apiUrl;
    public $apiReportUrl;
    public $gameUrl;
    public $key;
    public $agent;
    protected $sandbox = false;

    const ENV_APP = [
        'test' => [
            'api_url' => 'https://agcr.neverbet.me/api/',
            'report_api' => '',
            'game_url' => '',
            'key' => '',
            'agent' => '',
        ],
        'production' => [
            'api_url' => 'https://agcr.neverbet.me/api/',
            'report_api' => '',
            'game_url' => '',
            'key' => '',
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
        $this->agent = $mode['agent'];
        $this->apiUrl = $mode['api_url'];
        $this->apiReportUrl = $mode['report_api'];
        $this->gameUrl = $mode['game_url'];

        if(!empty($key)) { // If key not default
            $this->key = $key['key'];
        }
    }

    public function getCode($id){
        return self::CODE[$id];
    }

    public function setParam($data, $action, $prefix = null, $suffix = null, $url = null)
    {
        $arrData = array(
            't' => $this->key,
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
        // $param = urldecode($param);

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

        // $data_post = json_encode($data, JSON_UNESCAPED_UNICODE);
        $data_post = http_build_query($data);
        $data_post = urldecode($data_post);

        $header = array(
            "Content-Type: application/x-www-form-urlencoded",

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

    public function getBetItems($key = 0, $debug = false)
    {

        $setParam = [
            'keyOrdate' => 'key'
        ];

        $setSuffix = [
            'from' => date("Y-m-d\TH:i:s", strtotime(genDate('-680 minutes'))),
            'to' => date("Y-m-d\TH:i:s", strtotime(genDate('-660 minutes'))),
            'versionkey' => $key
        ];

        $param = $this->setParam($setParam, 'repullBettingHistoryApiClient.ashx', ['providercode' => 'IB'], $setSuffix, $this->apiReportUrl);

        $response = $this->push(false);

        if($debug) {
            return compact('param', 'response');
        }

        $response = json_decode($response, true);

        return $response;
    }

    public function getBetItemsByKey($debug = false)
    {

        $setParam = [];
        $setPrefix = [];
        $setSuffix = [
            'versionkey' => 0
        ];

        $param = $this->setParam($setParam, 'fetchbykey.aspx', $setPrefix, $setSuffix, $this->apiReportUrl);

        $response = $this->push(false);

        if($debug) {
            return compact('param', 'response');
        }

        $response = json_decode($response, true);

        return $response;
    }
}
