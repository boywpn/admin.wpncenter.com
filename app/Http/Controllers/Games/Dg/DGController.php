<?php

namespace App\Http\Controllers\Games\Dg;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DGController extends Controller
{

    public $apiurl = 'http://api.dg99web.com/';
    public $agentuser = 'DG00350201';
    public $apikey = '56920d844ab74c49a49cbb3f101f8886';

    public $token;
    public $strrandom;

    public $ch;
    public $data;
    public $url;

    const BET_LIMIT = [
        '0' => [
            'title' => 'all_game_type',
            'values' => [
                '1' => [
                    'key' => 'B',
                    'label' => '50-5,000',
                    'default' => 1
                ],
                '2' => [
                    'key' => 'D',
                    'label' => '100-10,000',
                    'default' => 0
                ],
                '3' => [
                    'key' => 'H',
                    'label' => '500-500,000',
                    'default' => 0
                ],
                '4' => [
                    'key' => 'I',
                    'label' => '10,000-1,000,000',
                    'default' => 0
                ]
            ],
            'type' => 'single'
        ]
    ];
    const BET_LIMIT_TYPE = 'single';

    const CODE = [
        '0'=>'Operation Successful',
        '1'=>'Parameter Error',
        '2'=>'Token Verification Failed',
        '3'=>'Command Not Find',
        '4'=>'Illegal Operation',
        '10'=>'Date format error',
        '11'=>'Data format error',
        '97'=>'Permission denied',
        '98'=>'Operation failed',
        '99'=>'Unknown Error',
        '100'=>'Account is locked',
        '101'=>'Account format error',
        '102'=>'Account does not exist',
        '103'=>'This account is taken',
        '104'=>'Password format error',
        '105'=>'Password wrong',
        '106'=>'New & Old Password is the same',
        '107'=>'Member account unavailable',
        '108'=>'Login Error',
        '109'=>'Signup Error',
        '110'=>'This account has been signed in',
        '111'=>'This account has been signed out',
        '112'=>'This account is not signed in',
        '113'=>'The Agent account inputted is not an Agent account',
        '114'=>'Member not found',
        '116'=>'Account occupied',
        '117'=>'Can not find branch of member',
        '118'=>'Can not find the specified Agent',
        '119'=>'Insufficent funds during Agent withdrawal',
        '120'=>'Insufficient balance',
        '121'=>'Profit limit must be greater than or equal to 0',
        '150'=>'Ran out of free demo accounts',
        '300'=>'system maintenance',
        '320'=>'Wrong API key',
        '321'=>'Limit Group Not Found',
        '322'=>'Currency Name Not Found',
        '323'=>'Use serial numbers for Transfer',
        '324'=>'Transfer failed',
        '325'=>'Agent Status Unavailable',
        '326'=>'Members Agent No video group',
        '400'=>'Client IP Restricted',
        '401'=>'Network latency',
        '402'=>'The connection is closed',
        '403'=>'Clients limited sources',
        '404'=>'Resource requested does not exist',
        '405'=>'Too frequent requests',
        '406'=>'Request timed out',
        '407'=>'Can not find game address',
        '500'=>'Null pointer exception',
        '501'=>'System Error',
        '502'=>'The system is busy',
        '503'=>'Data operation error'
    ];

    public function __construct($key = null)
    {
        //
        if(!empty($key)) { // If key not default
            $this->setKey($key);
        }

    }

    public function setKey($key){

        $this->agentuser = $key['account'];
        $this->apikey = $key['api_key'];

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

    public function getCode($id){
        return self::CODE[$id];
    }

    public function genStrRandom(){
        $this->strrandom = str_random(6);
    }

    public function genToken(){
        // Generate str random first
        $this->genStrRandom();
        $key = $this->agentuser . $this->apikey . $this->strrandom;
        $this->token = md5($key);

        return $key;
    }

    public function setParam($data, $action)
    {

        // Token
        $this->genToken();
        // Set Data Member
        $arrData = array(
            'token' => $this->token,
            'random' => $this->strrandom,
            'data' => 'B'
        );

        $arrMerge = array_merge($arrData, $data);

        // print_r($arrMerge);

        $this->data = json_encode($arrMerge);

        $this->url = $this->apiurl.$action."/".$this->agentuser."/";

    }

    // function for all request !!! important
    public function push()
    {
        $response = $this->pushAPI();

        return $response;
    }

    public function pushAPI(){

        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $this->url);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->data);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
            )
        );

        $response = curl_exec($this->ch);
        $err = curl_error($this->ch);

        curl_close($this->ch);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return json_decode($response, true);
        }

    }

    public function getBetItem()
    {
        $setParam = [];
        $this->setParam($setParam, 'game/getReport');
        $response = $this->push();

        return $response;
    }

    public function markBetItem($id)
    {
        $setParam = [
            'list' => $id
        ];
        $this->setParam($setParam, 'game/markReport');
        $response = $this->push();

        return $response;
    }
}
