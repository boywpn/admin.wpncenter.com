<?php

namespace Modules\Upc\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Api\Traits\RespondTrait;
use Modules\Member\Members\Entities\Members;
use Modules\Platform\Core\Repositories\GenericRepository;

class UpcController extends Controller
{
    use RespondTrait;

    /**
     * @var \Illuminate\Contracts\Routing\ResponseFactory
     */
    protected $response;

    public $datetime;

    const CODEID = [
        '0' => 'Success',
        '1' => 'Access Denied',
        '2' => 'Token is required',
        '3' => 'Authenticate Token have problem',
        '4' => 'Resource have problem.',

        '101' => 'กรุณาระบุ Username และ Password ให้ถูกต้อง',
        '102' => 'Username และ Password ไม่ถูกต้อง กรุณาลองใหม่',
        '103' => 'กรุณาระบุข้อมูลให้ครบทุกส่วน',
        '104' => 'Token ไม่ถูกต้อง',
        '105' => 'Password เดิมไม่ตรงกับในระบบ',
        '106' => 'Username นี้ไม่สามารถใช้ได้ หรือมีอยู่ในระบบแล้ว',
        '107' => 'Email ได้ใช้ลงทะเบียนแล้ว ไม่สามารถใช้ซ้ำได้',
        '108' => 'Username ไม่มีในระบบ กรุณาลองใหม่',
        '109' => 'Agent ไม่มีในระบบ กรุณาลองใหม่',
        '110' => 'ไม่มีในระบบ กรุณาลองใหม่',
        '111' => 'เบอร์โทรศัพท์ มีในระบบแล้ว กรุณาสอบถามเจ้าหน้าที่',
        '112' => 'เบอร์โทรศัพท์ ต้องมีตัวเลข 10 ตัวเลขเท่านั้น',

        '113' => 'เลขบัญชี ต้องมีตัวเลข 10 ตัวเลขเท่านั้น',
        '114' => 'เลขบัญชี ต้องมีตัวเลข 12 ตัวเลขเท่านั้น',

        '201' => 'Username ไม่มีในระบบ กรุณาลองใหม่',
        '202' => 'ขณะนี้ระบบยังไม่พร้อมใช้งาน',
        '203' => 'มีปัญหาเกิดขึ้นในระบบ',

        // For Wallet Stat
        '401' => 'Username และ Password ไม่ถูกต้อง กรุณาลองใหม่',
        '402' => 'ไม่สามารถสร้าง Token ได้',
        '403' => 'กรุุณากรอกข้อมูล ตามที่กำหนดไว้',
        '404' => 'ไม่มี Username นี้ในระบบ',
        '405' => 'Token Expired',
        '406' => 'Token Invalid',
        '407' => 'Token Absent',

        '501' => 'ยังไม่มีข้อมูลสมาชิกในระบบ Wallet กรุณาแจ้งฝาก',
        '502' => 'จำนวนเครดิตไม่เพียงพอ',
        '503' => 'Order ID นี้ไม่สามารถทำรายการซ้ำได้',
        '504' => 'Member ID ไม่มีในระบบ',
        '505' => 'ใส่ method ผิด กรุณาใช้เฉพาะ credit หรือ debit',
        '506' => 'จำนวนเงินไม่ต้องมีเครื่องหมาย , และมีทศนิยมไม่เกิน 2 ตำแหน่ง',
        '507' => 'ระบบไม่รองรับการปรับเครดิตของคาสิโนนี้',

        // For Created Wallet and Auto Order
        '601' => 'ไม่สามารถทำรายการได้เนื่องจาก ',
        '602' => 'รายการของท่านได้ส่งให้เจ้าหน้าที่ตรวจสอบเรียบร้อยแล้ว กรุณารอสักครู่...',
        '603' => 'ข้อมูลยืนยันไม่ตรงกับระบบ กรุณาตรวจสอบข้อมูลที่ส่งตรวจสอบ',

        // For Manual
        '701' => 'จำนวนเงินไม่ตรงกับรายการธนาคาร กรุณาตรวจสอบ!',
        '702' => 'ระบุ Type ไม่ถูกต้อง กรุณาระบุใหม่',

        // For Deposit
        '801' => 'ไม่สามารถทำรายการได้ในขณะนี้ กรุณาติดต่อเจ้าหน้าที่',
    ];

    const WALLET_MATHOD = [
        '1' => 'Statement to Wallet',
        '2' => 'Wallet to Game',
        '3' => 'Game to Wallet',
        '4' => 'Wallet to Bank',
        '5' => 'Event to Wallet',
        '6' => 'Void to Wallet',
        '7' => 'Void from Cancel',
    ];

    /**
     * Module Repository
     * @var
     */
    protected $repository = GenericRepository::class;

    protected $entityClass;

    protected $response_array = false;

    public function __construct()
    {
        // $this->middleware('upc');

        $this->datetime = date('Y-m-d H:i:s');
    }

    public function index(){

        return "Access Deny!";

    }

    /**
     * Create instance of module repository or use generic repository
     *
     * @return mixed
     */
    protected function getRepository($entityClass)
    {
        if ($this->repository == GenericRepository::class) {
            $repository = \App::make($this->repository);
            $repository->setupModel($entityClass);
        } else {
            $repository = \App::make($this->repository);
        }

        return $repository;
    }

    public function error($codeid, $data = [], $msg = null)
    {
        if(empty($msg)) {
            $msg = self::CODEID[$codeid];
        }
        if($this->response_array){
            return $this->respondWithCode(false, $codeid, $data, $msg);
        }else{
            return response()->json($this->respondWithCode(false, $codeid, $data, $msg), 400);
        }
    }
    public function errorMsg($codeid, $data = [], $msg = null)
    {
        if(empty($msg)) {
            $msg = self::CODEID[$codeid];
        }
        return $this->respondWithCode(false, $codeid, $data, $msg);
    }

    public function success($codeid, $data = [], $msg = null)
    {
        if(empty($msg)) {
            $msg = self::CODEID[$codeid];
        }
        if($this->response_array){
            return $this->respondWithCode(true, $codeid, $data, $msg);
        }else{
            return response()->json($this->respondWithCode(true, $codeid, $data, $msg), 200);
        }
    }
    public function successMsg($codeid, $data = [], $msg = null)
    {
        if(empty($msg)) {
            $msg = self::CODEID[$codeid];
        }
        return $this->respondWithCode(true, $codeid, $data, $msg);
    }

    public function mathcKey($token, $field, $type){

        if($type == 'wallet'){
            \cryptor::setKey(env('CRYPTOR_SALT_WALLET'), env('CRYPTOR_KEY_WALLET'));
        }else {
            \cryptor::setKey(env('CRYPTOR_SALT_FRONT'), env('CRYPTOR_KEY_FRONT'));
        }

        $key = \cryptor::decrypt($token);
        $ekey = explode("_", $key);
        if(!isset($ekey[1])){
            return $this->errorMsg(406);
        }
        $member_id = $ekey[0];
        $chk_key = $ekey[1];

        // check onetime key
        $ck_onetime = Members::where('id', $member_id)
            ->where($field, $chk_key)
            ->first();
        if(!$ck_onetime){
            return $this->errorMsg(406);
        }

        return $this->errorMsg(0, compact('member_id', 'chk_key'));

    }

    public function getCURL($url, $type, $post_data){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.103 Safari/537.36');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

        $data = curl_exec($ch);

        return $data;

    }

    public function checkAuth(){

        return $this->success('0', 'Token is good.');

    }
}
