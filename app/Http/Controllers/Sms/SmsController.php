<?php

namespace App\Http\Controllers\Sms;

use App\Http\Controllers\AppController;
use App\Models\Trnf\Banks;
use App\Models\Trnf\BanksAcc;
use App\Models\Trnf\BankStatement;
use App\Models\Trnf\Sms;
use App\Models\Trnf\TmpBankAuto;
use Illuminate\Http\Request;

/**
 *
 * Class ContactsApiController
 *
 * @package Modules\Api\Http\Controllers
 */
class SmsController extends AppController
{

    public function receive(Request $request){

        $sms = $request->input();
        $sms_json = json_encode($sms, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_UNICODE);

        //$dir = dirname(__FILE__);
        //file_put_contents($dir."/".date("Y-m-d").'.json', json_encode($sms, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_UNICODE)."\n", FILE_APPEND);

        $content = $request->input('content');

        if (!empty($sms)) {

            $lines = explode("\r\n", $content);
            $phone = $sms['receiver'];

            /**
             * Push sms to agent
             */
            if(in_array($phone, array('0612751844','0612267666'))){
                $ch = curl_init();
                $param = http_build_query($sms);
                curl_setopt($ch, CURLOPT_URL, "http://cash959bet.trnf.tk/api/sms/pushsms/getsms.php?".$param );
                curl_setopt($ch, CURLOPT_POSTFIELDS, $_GET);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
                $result = curl_exec($ch);
                exit;
            }

            $temp = explode('Sender: ', $lines[0]);
            $from = trim($temp[1]);

            $temp = explode('SMSC: ', $lines[2]);
            $sms_central = trim($temp[1]);

            $temp = explode('SCTS: ', $lines[3]);
            $time = trim($temp[1]);

            $message = trim($lines[5]);

            $this->entityClass = Sms::class;
            $repository = $this->getRepository();

            $banks = Banks::where('bank_smsref' ,'LIKE', '%|'.strtoupper($from).'%')->first();

            $otp = '0';
            if (strpos($message, 'OTP') !== false) {
                $otp = 1;
            }elseif (strpos($message, 'TOP') !== false) {
                $otp = 1;
            }elseif (strpos($message, 'Ref=') !== false) {
                $otp = 1;
            }

            $arrData = array(
                'sms_bank' => $from,
                'is_otp' => $otp,
                'bank_id' => (!empty($banks)) ? $banks->bank_id : null,
                'sms_number' => $phone,
                'sms_msg' => $message,
                'sms_data' => $sms_json,
                'sms_time' => $time,
                'sms_date' => date("Y-m-d H:i:s"),
                'created_at' => date("Y-m-d H:i:s")
            );

            $entity = $repository->createEntity($arrData, \App::make($this->entityClass));

            // Save to Statement
            if($banks->bank_id == 6){

                $bank_acc = BanksAcc::where('bank_id', $banks->bank_id)->where('bank_phone', $phone)->first();

                $arr = null;
                $msg = $message;
                preg_match('/Transfer from (.*?) amount THB (.*?) to your account x(.*?) on ([0-9]{2})\/([0-9]{2})\@(.*?)\.  Avai. Bal. is THB (.*?)\./', $msg, $arr); // Transfer from SUPACHAI  JIA amount THB 5,000.00 to your account x344136 on 13/04@01:24.  Avai. Bal. is THB 86,187.47.
                // preg_match('/Withdrawal\/transfer transaction amount THB (.*?) from your account x(.*?) via (.*?) on ([0-9]{2})\/([0-9]{2})\@(.*?)\. Available balance is THB (.*?)\./', $msg, $arr2); // Withdrawal/transfer transaction amount THB 1,100.00 from your account x342003 via ENET on 13/04@16:49. Available balance is THB 6,174.80.
                preg_match('/(.*?) Baht transferred to (.*?), if in doubt call 027776780/', $msg, $arr3); // 4,000.00 Baht transferred to นาย ภูวนารถ คังคัสโร, if in doubt call 027776780
                preg_match('/Transfer from (.*?)\/x(.*?) amount THB (.*?) to your account x(.*?)  on ([0-9]{2})\/([0-9]{2})\@(.*?)  Available balance is THB (.*?)\./', $msg, $arr4); // Transfer from BAYA/x481033 amount THB 1,000.00 to your account x343261  on 15/04@03:05  Available balance is THB 19,044.68.
                preg_match('/Cash\/transfer deposit amount THB (.*?) via (.*?) to your A\/C x(.*?) on ([0-9]{2})\/([0-9]{2})\@(.*?) Avai. Bal. is THB (.*?)\./', $msg, $arr5); // Cash/transfer deposit amount THB 200.00 via ATM to your A/C x344136 on 15/04@03:47 Avai. Bal. is THB 48,673.47.
                preg_match('/Transfer amount THB (.*?) to your account x(.*?) on ([0-9]{2})\/([0-9]{2})\@(.*?)\./', $msg, $arr6); // Transfer amount THB 100.00 to your account x296297 on 30/04@15:40.

                $datetime = date("Y-m-d H:i:s");
                $ref = null;
                $state_chanel = 'SMS';
                $state_tran_type = 'SMS';
                $state_detail = $msg;
                $wd = 0;
                $dp = 0;
                $amount = null;
                $balance = 0;

                if($arr){
                    $ref = $arr[1];
                    $amount = $arr[2];
                    $amount = floatval(preg_replace('/[^\d.]/', '', $amount));
                    $d = $arr[4];
                    $m = $arr[5];
                    $time = $arr[6];
                    $balance = $arr[7];
                    $balance = floatval(preg_replace('/[^\d.]/', '', $balance));
                    $y = date('Y');

                    $date = $y . "-" . $m . "-" . $d;
                    $datetime = $date . " " . $time;
                    $datetime = date('Y-m-d H:i:s', strtotime($datetime));
                }
//                elseif($arr2){ // WD
//                    $arr = $arr2;
//
//                    $amount = $arr[1];
//                    $amount = floatval(preg_replace('/[^\d.]/', '', $amount));
//                    $amount = $amount * -1;
//                    $state_tran_type = $arr[3];
//                    $d = $arr[4];
//                    $m = $arr[5];
//                    $time = $arr[6];
//                    $balance = $arr[7];
//                    $balance = floatval(preg_replace('/[^\d.]/', '', $balance));
//                    $y = date('Y');
//
//                    $date = $y . "-" . $m . "-" . $d;
//                    $datetime = $date . " " . $time;
//                    $datetime = date('Y-m-d H:i:s', strtotime($datetime));
//                }
                elseif($arr3){ // WD
                    $arr = $arr3;

                    $ref = $arr[2];
                    $amount = $arr[1];
                    $amount = floatval(preg_replace('/[^\d.]/', '', $amount));
                    $amount = $amount * -1;

                }
                elseif($arr4){
                    $arr = $arr4;

                    $ref = $arr[2];
                    $amount = $arr[3];
                    $amount = floatval(preg_replace('/[^\d.]/', '', $amount));
                    $d = $arr[5];
                    $m = $arr[6];
                    $time = $arr[7];
                    $balance = $arr[8];
                    $balance = floatval(preg_replace('/[^\d.]/', '', $balance));
                    $y = date('Y');

                    $date = $y . "-" . $m . "-" . $d;
                    $datetime = $date . " " . $time;
                    $datetime = date('Y-m-d H:i:s', strtotime($datetime));
                }
                elseif($arr5){
                    $arr = $arr5;

                    $amount = $arr[1];
                    $amount = floatval(preg_replace('/[^\d.]/', '', $amount));
                    $state_tran_type = $arr[2];
                    $d = $arr[4];
                    $m = $arr[5];
                    $time = $arr[6];
                    $balance = $arr[7];
                    $balance = floatval(preg_replace('/[^\d.]/', '', $balance));
                    $y = date('Y');

                    $date = $y . "-" . $m . "-" . $d;
                    $datetime = $date . " " . $time;
                    $datetime = date('Y-m-d H:i:s', strtotime($datetime));
                }
                elseif($arr6){
                    $arr = $arr6;

                    $amount = $arr[1];
                    $amount = floatval(preg_replace('/[^\d.]/', '', $amount));
                    $d = $arr[3];
                    $m = $arr[4];
                    $time = $arr[5];
                    $y = date('Y');

                    $date = $y . "-" . $m . "-" . $d;
                    $datetime = $date . " " . $time;
                    $datetime = date('Y-m-d H:i:s', strtotime($datetime));
                }

                if($amount >= 0){
                    $dp = $amount;
                }else{
                    $wd = $amount * -1;
                }

                $ref = preg_replace('/\s+/', ' ', $ref);

                $hash = md5($state_detail);

                $arrData = array(
                    'bank_id' => $banks->bank_id,
                    'bank_number' => $bank_acc->bank_number,
                    'acc_id' => $bank_acc->acc_id,
                    'state_date' => $datetime,
                    'state_chanel' => $state_chanel,
                    'state_tran_type' => $state_tran_type,
                    'state_withdrawal' => (string)$wd,
                    'state_deposit' => (string)$dp,
                    'state_account_no' => $ref,
                    'state_hashkey' => $hash,
                    'state_detail' => $state_detail,
                    'created_at' => date("Y-m-d H:i:s"),
                    'created_use' => '0',
                    'updated_at' => date("Y-m-d H:i:s"),
                    'updated_use' => '0'
                );

                if($bank_acc->bank_sms == 96 && $banks->bank_sms_statement == 1) { // If enable sms statement
                    $this->insertStatement($arrData);
                }

            }

            print_r($entity);
        }

    }

    public function test(Request $request){

        $input = $request->all();

        $sms_number = $input['phone'];
        $bank_id = 6;
        $bank_acc = BanksAcc::where('bank_id', $bank_id)->where('bank_phone', $sms_number)->first();

        $arr = null;
        $msg = $input['content'];
        preg_match('/Transfer from (.*?) amount THB (.*?) to your account x(.*?) on ([0-9]{2})\/([0-9]{2})\@(.*?)\.  Avai. Bal. is THB (.*?)\./', $msg, $arr); // Transfer from SUPACHAI  JIA amount THB 5,000.00 to your account x344136 on 13/04@01:24.  Avai. Bal. is THB 86,187.47.
        // preg_match('/Withdrawal\/transfer transaction amount THB (.*?) from your account x(.*?) via (.*?) on ([0-9]{2})\/([0-9]{2})\@(.*?)\. Available balance is THB (.*?)\./', $msg, $arr2); // Withdrawal/transfer transaction amount THB 1,100.00 from your account x342003 via ENET on 13/04@16:49. Available balance is THB 6,174.80.
        preg_match('/(.*?) Baht transferred to (.*?), if in doubt call 027776780/', $msg, $arr3); // 4,000.00 Baht transferred to นาย ภูวนารถ คังคัสโร, if in doubt call 027776780
        preg_match('/Transfer from (.*?)\/x(.*?) amount THB (.*?) to your account x(.*?)  on ([0-9]{2})\/([0-9]{2})\@(.*?)  Available balance is THB (.*?)\./', $msg, $arr4); // Transfer from BAYA/x481033 amount THB 1,000.00 to your account x343261  on 15/04@03:05  Available balance is THB 19,044.68.
        preg_match('/Cash\/transfer deposit amount THB (.*?) via (.*?) to your A\/C x(.*?) on ([0-9]{2})\/([0-9]{2})\@(.*?) Avai. Bal. is THB (.*?)\./', $msg, $arr5); // Cash/transfer deposit amount THB 200.00 via ATM to your A/C x344136 on 15/04@03:47 Avai. Bal. is THB 48,673.47.
        preg_match('/Transfer amount THB (.*?) to your account x(.*?) on ([0-9]{2})\/([0-9]{2})\@(.*?)\./', $msg, $arr6); // Transfer amount THB 100.00 to your account x296297 on 30/04@15:40.

        $datetime = date("Y-m-d H:i:s");
        $ref = null;
        $state_chanel = 'sms';
        $state_tran_type = 'sms';
        $state_detail = $msg;
        $wd = 0;
        $dp = 0;
        $amount = null;
        $balance = 0;

        if($arr){
            $ref = $arr[1];
            $amount = $arr[2];
            $amount = floatval(preg_replace('/[^\d.]/', '', $amount));
            $d = $arr[4];
            $m = $arr[5];
            $time = $arr[6];
            $balance = $arr[7];
            $balance = floatval(preg_replace('/[^\d.]/', '', $balance));
            $y = date('Y');

            $date = $y . "-" . $m . "-" . $d;
            $datetime = $date . " " . $time;
            $datetime = date('Y-m-d H:i:s', strtotime($datetime));
        }
//        elseif($arr2){ // WD
//            $arr = $arr2;
//
//            $amount = $arr[1];
//            $amount = floatval(preg_replace('/[^\d.]/', '', $amount));
//            $amount = $amount * -1;
//            $state_tran_type = $arr[3];
//            $d = $arr[4];
//            $m = $arr[5];
//            $time = $arr[6];
//            $balance = $arr[7];
//            $balance = floatval(preg_replace('/[^\d.]/', '', $balance));
//            $y = date('Y');
//
//            $date = $y . "-" . $m . "-" . $d;
//            $datetime = $date . " " . $time;
//            $datetime = date('Y-m-d H:i:s', strtotime($datetime));
//        }
        elseif($arr3){ // WD
            $arr = $arr3;

            $ref = $arr[2];
            $amount = $arr[1];
            $amount = floatval(preg_replace('/[^\d.]/', '', $amount));
            $amount = $amount * -1;

        }
        elseif($arr4){
            $arr = $arr4;

            $ref = $arr[2];
            $amount = $arr[3];
            $amount = floatval(preg_replace('/[^\d.]/', '', $amount));
            $d = $arr[5];
            $m = $arr[6];
            $time = $arr[7];
            $balance = $arr[8];
            $balance = floatval(preg_replace('/[^\d.]/', '', $balance));
            $y = date('Y');

            $date = $y . "-" . $m . "-" . $d;
            $datetime = $date . " " . $time;
            $datetime = date('Y-m-d H:i:s', strtotime($datetime));
        }
        elseif($arr5){
            $arr = $arr5;

            $amount = $arr[1];
            $amount = floatval(preg_replace('/[^\d.]/', '', $amount));
            $state_tran_type = $arr[2];
            $d = $arr[4];
            $m = $arr[5];
            $time = $arr[6];
            $balance = $arr[7];
            $balance = floatval(preg_replace('/[^\d.]/', '', $balance));
            $y = date('Y');

            $date = $y . "-" . $m . "-" . $d;
            $datetime = $date . " " . $time;
            $datetime = date('Y-m-d H:i:s', strtotime($datetime));
        }
        elseif($arr6){
            $arr = $arr6;

            $amount = $arr[1];
            $amount = floatval(preg_replace('/[^\d.]/', '', $amount));
            $d = $arr[3];
            $m = $arr[4];
            $time = $arr[5];
            $y = date('Y');

            $date = $y . "-" . $m . "-" . $d;
            $datetime = $date . " " . $time;
            $datetime = date('Y-m-d H:i:s', strtotime($datetime));
        }

        if($amount >= 0){
            $dp = $amount;
        }else{
            $wd = $amount * -1;
        }

        $ref = preg_replace('/\s+/', ' ', $ref);

        $hash = md5($state_detail);

        return $arrData = array(
            'bank_id' => $bank_id,
            'bank_number' => $bank_acc->bank_number,
            'acc_id' => $bank_acc->acc_id,
            'state_date' => $datetime,
            'state_chanel' => $state_chanel,
            'state_tran_type' => $state_tran_type,
            'state_withdrawal' => (string)$wd,
            'state_deposit' => (string)$dp,
            'state_account_no' => $ref,
            'state_hashkey' => $hash,
            'state_detail' => $state_detail,
            'created_at' => date("Y-m-d H:i:s"),
            'created_use' => '0',
            'updated_at' => date("Y-m-d H:i:s"),
            'updated_use' => '0'
        );

        if($bank_acc->bank_sms == 96) { // If enable sms statement
            return $this->insertStatement($arrData);
        }

        return compact('arr', 'arrData', 'bank_acc');

    }

    public function insertStatement($arrData){

        $this->entityClass = BankStatement::class;
        $repository = $this->getRepository();

        $entity = $repository->createEntity($arrData, \App::make($this->entityClass));

        // Add to tmp
        $arrData['state_id'] = $entity['state_id'];
        $this->insertTempBank($arrData);

    }

    public function insertTempBank($data){

        $arrData = array(
            'state_id' => $data['state_id'],
            'acc_id' => $data['acc_id'],
            'bank_id' => $data['bank_id'],
            'bank_number' => $data['bank_number'],
            'state_account_no' => $data['state_account_no'],
            'state_date' => $data['state_date'],
            'state_deposit' => $data['state_deposit'],
            'state_detail' => $data['state_detail'],
            'created_at' => date("Y-m-d H:i:s")
        );

        $this->entityClass = TmpBankAuto::class;
        $repository = $this->getRepository();

        $entity = $repository->createEntity($arrData, \App::make($this->entityClass));

    }

}