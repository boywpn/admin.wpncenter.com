<?php

namespace App\Http\Controllers\Games\Sa;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SAController extends Controller
{

    public $apiurl = 'http://api.sa-apisvr.com/api/api.aspx';
    public $clienturl = 'https://ssh.sa-api3.net/app.aspx';
    public $lobby = 'A965';
    public $secretkey = 'AB6B121D85974705A81E628933C87727';
    public $md5key = 'GgaIMaiNNtg';
    public $encryptkey = 'g9G16nTs';
    public $aguser = 'sbobetsh';

    public $dir;
    public $token_file;
    public $token;
    public $time;
    public $url;
    public $qs;
    public $md5;

    public $ch;

//    const BET_LIMIT = [
//        '0' => [
//            'title' => 'all_game_type',
//            'values' => [
//                '1' => [
//                    'key' => 1,
//                    'label' => '5-500',
//                    'default' => 0
//                ],
//                '2' => [
//                    'key' => 17592186044416,
//                    'label' => '20-5,000',
//                    'default' => 0
//                ],
//                '3' => [
//                    'key' => 2048,
//                    'label' => '100-10,000',
//                    'default' => 0
//                ],
//                '4' => [
//                    'key' => 1048576,
//                    'label' => '500-50,000',
//                    'default' => 0
//                ],
//                '5' => [
//                    'key' => 4194304,
//                    'label' => '1,000-100,000',
//                    'default' => 0
//                ],
//                '6' => [
//                    'key' => 2199023255552,
//                    'label' => '5-3,000',
//                    'default' => 1
//                ],
//            ],
//            'type' => 'multi'
//        ]
//    ];
    const BET_LIMIT = [
        '0' => [
            'key' => 1,
            'label' => '5-500',
            'default' => 0
        ],
        '1' => [
            'key' => 2199023255552,
            'label' => '5-3,000',
            'default' => 1
        ],
        '2' => [
            'key' => 17592186044416,
            'label' => '20-5,000',
            'default' => 0
        ],
        '3' => [
            'key' => 2048,
            'label' => '100-10,000',
            'default' => 0
        ],
        '4' => [
            'key' => 1048576,
            'label' => '500-50,000',
            'default' => 0
        ],
        '5' => [
            'key' => 4194304,
            'label' => '1,000-100,000',
            'default' => 0
        ],
    ];
    const BET_LIMIT_TYPE = 'multi';

    public function __construct($key = null)
    {
        //
        if(!empty($key)) { // If key not default
            $this->secretkey = $key['secret_key'];
            $this->md5key = $key['md5_key'];
            $this->encryptkey = $key['encrypt_key'];
        }
    }

    public function setParam($data)
    {
        $this->time = date("YmdHis");
        $arrData = array(
            'Key' => $this->secretkey,
            'Time' => $this->time
        );

        $arrMerge = array_merge($arrData, $data);
        $this->url = http_build_query($arrMerge);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function genQS(){
        $crypt = new DES($this->encryptkey);
        $mstr = $crypt->encrypt($this->url);
        $this->qs = $mstr;
        //echo "\r\n\r\n";
        return $this->qs;
    }

    /**
     * MD5 function
    */
    public function genMD5(){
        $PreMD5Str = $this->url . $this->md5key . $this->time . $this->secretkey;
        //echo "\r\n\r\n";
        $this->md5 = md5($PreMD5Str);
        //echo "\r\n\r\n";
        return $this->md5;
    }

    /**
     * function for all request !!! important
    */
    public function push()
    {
        $data = [
            "q" => $this->genQS(),
            "s" => $this->genMD5()
        ];

        $response = $this->pushAPI($data);

        return $response;
    }

    public function pushAPI($data, $post = true){

        //print_r($data);

        $data_post = http_build_query($data);

        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $this->apiurl);
        if($post) {
            curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data_post);
        }
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($this->ch);
        $err = curl_error($this->ch);

        curl_close($this->ch);

        // echo $response;

        if ($err) {
            return "cURL Error #:" . $err;
        } else {

            // Check error from api
//            $response = json_decode($response, true);
//            if($res['status'] == "error"){
//                if(isset($res['meta']['token']) && $res['meta']['token'] == "invalid token."){
//                    $this->genToken();
//                    $response = $this->pushAPI($url, $data, $post);
//                }
//            }

            return $response;
        }

    }

    public function getUser($username)
    {
        $this->time = date("YmdHis");
        $arrData = array(
            'Key' => $this->secretkey,
            'Time' => $this->time,
            'method' => 'GetUserStatusDV',
            'Username' => $username
        );
        $this->url = http_build_query($arrData);
        $response = $this->push();
        $res = xmlDecode($response, true);

        return $res;
    }

    public function getBetItem($username, $offset = 0)
    {
        $this->time = date("YmdHis");
        $arrData = array(
            'Key' => $this->secretkey,
            'Time' => $this->time,
            'method' => 'GetUserBetItemDV',
            'Username' => $username,
//            'FromTime' => genDate('-2 day', false)." 00:00:00",
//            'ToTime' => genDate('-0 day', false)." 23:59:59",
            'FromTime' => genDate('30 minutes'),
            'ToTime' => genDate('60 minutes'),
            'Offset' => $offset
        );
        $this->url = http_build_query($arrData);
        $response = $this->push();
        $res = xmlDecode($response, true);

        return $res;
    }

    public function getBetItems()
    {
        $this->time = date("YmdHis");
        $arrData = array(
            'Key' => $this->secretkey,
            'Time' => $this->time,
            'method' => 'GetAllBetDetailsForTimeIntervalDV',
            'FromTime' => genDate('50 minutes'),
            'ToTime' => genDate('60 minutes'),
        );
        $this->url = http_build_query($arrData);
        $response = $this->push();
        $res = xmlDecode($response, true);

        return $res;
    }

    public function getBetItemsFixtime()
    {
        $this->time = date("YmdHis");
        $arrData = array(
            'Key' => $this->secretkey,
            'Time' => $this->time,
            'method' => 'GetAllBetDetailsForTimeIntervalDV',
            'FromTime' => '2020-02-21 12:00:00',
            'ToTime' => '2020-02-22 11:59:59',
        );
        $this->url = http_build_query($arrData);
        $response = $this->push();
        $res = xmlDecode($response, true);

        return $res;
    }

    public function setBetLimit($username, $limit)
    {
        $this->time = date("YmdHis");
        $arrData = array(
            'Key' => $this->secretkey,
            'Time' => $this->time,
            'method' => 'SetBetLimit',
            'Username' => $username,
            'Currency' => 'THB',
        );

        foreach ($limit as $key => $id){
            $arrData[$key] = $id;
        }

        $this->url = http_build_query($arrData);

        $response = $this->push();
        $res = xmlDecode($response, true);

        return $res;
    }

    public function getBetLimitList()
    {
        $this->time = date("YmdHis");
        $arrData = array(
            'Key' => $this->secretkey,
            'Time' => $this->time,
            'method' => 'QueryBetLimit',
            'Currency' => 'THB'
        );
        $this->url = http_build_query($arrData);
        $response = $this->push();
        $res = xmlDecode($response, true);

        return $res;
    }
}
