<?php

namespace App\Http\Controllers\Games\Og;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OGController extends Controller
{

    public $apiurl = 'http://api01.oriental-game.com:8085/';
    public $apifetchurl = 'http://mucho.oriental-game.com:8057/';
    public $apioperator = 'mog118wac';
    public $apikey = '135niY2lpAhVcvHh';

    public $dir;
    public $token_file;
    public $token;

    public $ch;
    public $data;
    public $url;

    const BET_LIMIT = [
        '0' => [
            'title' => 'video',
            'values' => [
                '1' => [
                    'key' => 2,
                    'label' => '10-1,000',
                    'default' => 1
                ],
                '2' => [
                    'key' => 9,
                    'label' => '100-10,000',
                    'default' => 0
                ],
                '3' => [
                    'key' => 16,
                    'label' => '300-30,000',
                    'default' => 0
                ],
                '4' => [
                    'key' => 23,
                    'label' => '1,000-50,000',
                    'default' => 0
                ],
                '5' => [
                    'key' => 29,
                    'label' => '10,000-200,000',
                    'default' => 0
                ],
                '6' => [
                    'key' => 43,
                    'label' => '30,000-300,000',
                    'default' => 0
                ]
            ],
            'type' => 'single'
        ],
        '1' => [
            'title' => 'roulette',
            'values' => [
                '1' => [
                    'key' => 13,
                    'label' => '10-3,000',
                    'default' => 1
                ],
                '2' => [
                    'key' => 17,
                    'label' => '10-5,000',
                    'default' => 0
                ],
                '3' => [
                    'key' => 8,
                    'label' => '1,000-10,000',
                    'default' => 0
                ],
                '4' => [
                    'key' => 14,
                    'label' => '1,000-100,000',
                    'default' => 0
                ],
                '5' => [
                    'key' => 9,
                    'label' => '5,000-50,000',
                    'default' => 0
                ]
            ],
            'type' => 'single'
        ]
    ];
    const BET_LIMIT_TYPE = 'single';

    public function __construct($key)
    {
        //
        $this->apioperator = $key['x_operator'];
        $this->apikey = $key['x_key'];

    }

    public function setToken()
    {
        $this->dir = dirname(__FILE__);
        $this->token_file = $this->dir . '/token/' . $this->apioperator . '.txt';

        // check token exist
        if (file_exists($this->token_file)) {
            $this->token = file_get_contents($this->token_file);
            //print "Token already is " . $this->token . "\r\n";
        }else{
            $this->genToken();
            //print "Create token key \r\n";
        }
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

    public function genToken(){

        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $this->apiurl."token");
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array(
                'X-Operator: ' . $this->apioperator,
                'X-key: ' . $this->apikey
        ));

        $response = curl_exec($this->ch);
        $err = curl_error($this->ch);

        curl_close($this->ch);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            if(empty($response)){
                return false;
            }

            $response = json_decode($response, true);
            // print_r($response);
            if($response['status'] != "success"){
                return false;
            }

            $this->token = $response['data']['token'];

            // Keep Token to files
            file_put_contents($this->token_file, $this->token);

            return true;
        }

    }

    public function setParam($data, $action, $post = true)
    {

//        print "\r\nSet Parameter\r\n";
//        print_r($data);
        $this->data = http_build_query($data);

        if($post) {
            $this->url = $this->apiurl . $action;
        }else{
            $this->url = $this->apiurl . $action . '?' . $this->data;
        }

    }

    public function pushAPI($post = true){

        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $this->url);
        if($post) {
            curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->data);
        }
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array(
                'X-Token: ' . $this->token)
        );

        $response = curl_exec($this->ch);
        $err = curl_error($this->ch);

        curl_close($this->ch);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {

            // Check error from api
            $res = json_decode($response, true);
            if($res['status'] == "error"){
                if(isset($res['meta']['token']) && $res['meta']['token'] == "invalid token."){
                    $this->genToken();
                    $response = $this->pushAPI($post);
                }
            }

            if(!$response){
                return [
                    'status' => 'error',
                    'data' => [
                        'message' => 'สร้างไม่สำเร็จ กรุณาทำใหม่อีกครั้ง'
                    ]
                ];
            }

            return json_decode($response, true);
        }

    }

    public function pushFetchAPI($url, $data, $post = true){

        date_default_timezone_set("GMT");

        $s_date = date('Y-m-d H:i:s', strtotime("-20 minute", strtotime(gmdate('Y-m-d H:i:s'))));
        $e_date = date('Y-m-d H:i:s', strtotime("10 minute", strtotime($s_date)));

        // Set Data Member
        $arrData = array(
            'Operator' => $this->apioperator,
            'Key' => $this->apikey,
            'SDate' => $s_date,
            'EDate' => $e_date,
//            'SDate' => '2019-02-28 11:25:00',
//            'EDate' => '2019-02-28 11:35:00',
        );

        $arrMerge = array_merge($arrData, $data);

        print_r($arrMerge);
        print "\r\n".$url."\r\n\r\n";

        $data_post = http_build_query($arrMerge);

        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $url);
        if($post) {
            curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data_post);
        }
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($this->ch);
        $err = curl_error($this->ch);

        curl_close($this->ch);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }

    }
}
