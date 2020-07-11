<?php

namespace App\Http\Controllers\Games\Sexy;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SexyController extends Controller
{

    public $apiUrl;
    public $gameUrl;
    public $key;
    public $agent;
    protected $sandbox = false;

    const ENV_APP = [
        'test' => [
            'api_url' => 'http://testapi.onlinegames22.com/',
            'game_url' => '',
            'key' => 'p03l6Yq2naJMVlpZRxz',
            'agent' => 'wpnag',
        ],
        'production' => [
            'api_url' => 'http://api.onlinegames22.com/',
            'game_url' => '',
            'key' => 'PppWrWakkHuy16Pyaoc',
            'agent' => 'wpnagprod',
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
        "9999" => "Fail",
        "0000" => "Success",
        "10" => "please input all data!",
        "1000" => "Invalid user Id",
        "1001" => "Account existed",
        "1002" => "Account is not exists",
        "1003" => "Operation ID does not exist",
        "1004" => "Invalid Currency",
        "1005" => "language is not exists",
        "1006" => "PT Setting is empty!",
        "1007" => "Invalid PT setting with parent!",
        "1008" => "Invalid token!",
        "1009" => "Invalid timeZone",
        "1010" => "Invalid amount",
        "1011" => "Invalid txCode",
        "1012" => "Has Pending Transfer",
        "1013" => "Account is Lock",
        "1014" => "Account is Suspend",
        "1015" => "Account is Close",
        "1016" => "TxCode already operation!",
        "1017" => "TxCode is not exist",
        "1018" => "Not Enouth Balance",
        "1019" => "No Data",
        "1020" => "Cashier Id is not exists",
        "1021" => "Cashier Id is not outlet downline",
        "1022" => "User Id is not operation downline",
        "1023" => "Invalid House Id",
        "1024" => "Invalid date time format",
        "1025" => "Invalid transaction status",
        "1026" => "Invalid bet limit setting",
        "1027" => "Invalid Certificate",
        "1028" => "Unable to proceed. please try again later.",
        "1029" => "invalid IP address.",
        "1030" => "invalid Device.",
        "1031" => "System is under maintenance.",
        "1032" => "Duplicate login.",
        "1033" => "Invalid Game.",
        "1034" => "Time does not meet.",
        "1035" => "Invalid Agent Id.",
        "1036" => "Invalid parameters."
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
            'cert' => $this->key,
            'agentId' => $this->agent,
        );

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
