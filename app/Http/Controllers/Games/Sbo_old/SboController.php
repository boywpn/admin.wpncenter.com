<?php

namespace App\Http\Controllers\Games\Sbo_old;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SboController extends Controller
{

    public $apiurl;
    public $gameurl;
    public $salt;
    public $key;
    public $code;
    protected $sandbox = true;

    const ENV_APP = [
        'test' => [
            'api_url' => 'http://api-sbt.ionclubtry.com/',
            'game_url' => 'http://wwwsbt1.ionclubtry.com/',
            'salt' => 'salt',
            'key' => '1234567890123456',
            'merchant_code' => 'SBT',
        ],
        'production' => [
            'api_url' => 'http://api-sbt.ionclubtry.com/',
            'game_url' => 'http://wwwsbt1.ionclubtry.com/',
            'salt' => 'salt',
            'key' => '1234567890123456',
            'merchant_code' => 'SBT',
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
    const CODE = [];

    public function __construct($key = null)
    {

        date_default_timezone_set('America/Lima');

        //
        if($this->sandbox){
            $mode = self::ENV_APP['test'];
        }else{
            $mode = self::ENV_APP['production'];
        }
        $this->salt = $mode['salt'];
        $this->key = $mode['key'];
        $this->merchant_code = $mode['merchant_code'];
        $this->api_url = $mode['api_url'];
        $this->game_url = $mode['game_url'];

        if(!empty($key)) { // If key not default
            $this->salt = $key['salt'];
            $this->key = $key['key'];
            $this->merchant_code = $key['merchant_code'];
        }
    }

    public function getCode($id){
        return self::CODE[$id];
    }

    public function setParam($data, $unset = [], $action)
    {

        $timestamp = date("Y-m-d\TH:i:s");
        $data_checksum = $data;
        foreach ($unset as $set){
            unset($data_checksum[$set]);
        }

        $md5 = implode(".",$data_checksum).".".$timestamp.".".$this->salt;
        $strMd5 = md5($md5, true);

        $arrData = array(
            'Timestamp' => $timestamp,
            'Checksum' => base64_encode($strMd5),
        );

        $data_request = array_merge($arrData, $data);

        $this->data = json_encode($data_request);

        $url = $this->api_url.$action;
        $this->url = $url;

        return compact('md5', 'url', 'data', 'data_request');
    }

    public function setPayLoad($data)
    {

        $timestamp = date("Y-m-d\TH:i:s");
        $data_checksum = $data;

        $str = implode(".",$data_checksum).".".$this->merchant_code.".".$timestamp.".".$this->salt;
        $strMd5 = md5($str, true);
        $checksum = base64_encode($strMd5);
        $getFields["Checksum"] = $checksum;
        $data_string = json_encode($getFields);
        $strPlainText = $str . "." . $checksum;
        $key = $this->key;

        $url = 'https://admin.wpnadmin.com/mcrypt.php?key='.$key.'&text='.$strPlainText;
        $payload = file_get_contents('https://admin.wpnadmin.com/mcrypt.php?key='.$key.'&text='.$strPlainText);

        $data_request = [
            'payload' => $payload,
//            'AccountId' => $data_checksum['AccountId'],
//            'Currency' => $data_checksum['Currency'],
//            'MerchantCode' => $this->merchant_code,
        ];
        $this->url = "http://wwwsbt1.ionclubtry.com/middleware/v2/Dispatch/Game/SBO/SPORTS/DESKTOP/th-TH";

        $data_post = http_build_query($data_request);
//        $header = array(
//            "content-type: application/json",
//            "content-length: ".strlen($data_post)
//        );
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $this->url);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data_post);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($this->ch, CURLOPT_HTTPHEADER, $header);

        $response = curl_exec($this->ch);
        $err = curl_error($this->ch);

        // return compact('str', 'key', 'checksum', 'strPlainText', 'url', 'payload', 'data_post', 'response');
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
            "content-type: application/json",
            "content-length: ".strlen($data_post)
        );
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $this->url);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data_post);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
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

//    public function getBetItem($username, $offset = 0)
//    {
//        $this->time = date("YmdHis");
//        $arrData = array(
//            'Key' => $this->secretkey,
//            'Time' => $this->time,
//            'method' => 'GetUserBetItemDV',
//            'Username' => $username,
//            'FromTime' => genDate('30 minutes'),
//            'ToTime' => genDate('60 minutes'),
//            'Offset' => $offset
//        );
//        $this->url = http_build_query($arrData);
//        $response = $this->push();
//        $res = xmlDecode($response, true);
//
//        return $res;
//    }
//
//    public function setBetLimit($username, $limit)
//    {
//        $this->time = date("YmdHis");
//        $arrData = array(
//            'Key' => $this->secretkey,
//            'Time' => $this->time,
//            'method' => 'SetBetLimit',
//            'Username' => $username,
//            'Currency' => 'THB',
//        );
//
//        foreach ($limit as $key => $id){
//            $arrData[$key] = $id;
//        }
//
//        $this->url = http_build_query($arrData);
//
//        $response = $this->push();
//        $res = xmlDecode($response, true);
//
//        return $res;
//    }
//
//    public function getBetLimitList()
//    {
//        $this->time = date("YmdHis");
//        $arrData = array(
//            'Key' => $this->secretkey,
//            'Time' => $this->time,
//            'method' => 'QueryBetLimit',
//            'Currency' => 'THB'
//        );
//        $this->url = http_build_query($arrData);
//        $response = $this->push();
//        $res = xmlDecode($response, true);
//
//        return $res;
//    }
}
