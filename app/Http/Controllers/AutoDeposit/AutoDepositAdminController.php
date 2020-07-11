<?php
namespace App\Http\Controllers\AutoDeposit;

use App\Http\Controllers\AppController;
use App\Models\Old\BanksSystem;
use App\Models\Old\Members;
use App\Models\Trnf\BankStatement;
use App\Models\Trnf\TmpBankAutoRequest;
use App\Models\Trnf\TmpBankAuto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AutoDepositAdminController extends AppController
{

    protected $lists;
    protected $total;

    const DOMAIN = [
        1 => 'autobet168',
        2 => 'max168',
        3 => 'gc168vip',
        4 => 'sbobetsh',
        5 => 'webpanun',
    ];

    public function test_pusher(){

        pusherSend('Test', 'auto-report', 'statement');

    }

    public function index()
    {
        $lists = $this->getList();

        $data = [];
        foreach ($lists as $list){

            $arrData = [
                'statement_id' => $list['state_id'],
                'statement_date' => $list['state_date'],
                'statement_account_no' => $list['state_account_no'],
                'web_bank_name' => $list['bank_name'],
                'web_bank_account' => $list['bank_account'],
                'web_bank_number' => $list['bank_number'],
                'amount' => $list['state_deposit'],
                'member' => [
                    'domain' => $list['match_data']['member'][0]['domain'],
                    'domain_name' => self::DOMAIN[$list['match_data']['member'][0]['domain']],
                    'name' => $list['match_data']['member'][0]['withdraw_name'],
                    'bank_number' => $list['match_data']['member'][0]['withdraw_ac'],
                ],
                'auto_transfer' => ($list['bank_auto_credit'] == 96) ? 1 : 0
            ];

            pusherSend($arrData, 'auto-report', 'statement');

            $data[] = $arrData;

            // Send noti to line group
            $textnoti[$list['state_id']] = "\r\nรายการฝาก: ".$list['state_id'];
            $textnoti[$list['state_id']] .= "\r\nจำนวน: ".$list['state_deposit'];
            $textnoti[$list['state_id']] .= "\r\nเวลาธนาคาร: ".$list['state_date'];
            $textnoti[$list['state_id']] .= "\r\nธนาคาร: ".$list['bank_name'];
            $textnoti[$list['state_id']] .= "\r\nชื่อบัญชี: ".$list['bank_account'];
            $textnoti[$list['state_id']] .= "\r\nเลขบัญชี: ".$list['bank_number'];
            $textnoti[$list['state_id']] .= "\r\n";
            $textnoti[$list['state_id']] .= "\r\nโอนจาก: ".$list['state_account_no'];
            $textnoti[$list['state_id']] .= "\r\nDomain: ".self::DOMAIN[$list['match_data']['member'][0]['domain']];
            $textnoti[$list['state_id']] .= "\r\nMember Name: ".$list['match_data']['member'][0]['withdraw_name'];
            $textnoti[$list['state_id']] .= "\r\nMember Bank: ".$list['match_data']['member'][0]['withdraw_ac'];

        }

        $arrData = [
            'total' => $this->total,
            'total_match' => count($data),
            'datetime' => date('Y-m-d H:i:s'),
            'lists' => $data
        ];

//         return $arrData;

        $auto = $this->postAuto($arrData);
        $response = json_decode($auto, true);

        $datetime = date('Y-m-d H:i:s');
        foreach ($response['lists'] as $id => $list){
            $json = json_encode($list, JSON_UNESCAPED_UNICODE);

            $order_id = ($list['status'] == 'true') ? $list['workspace']['id'] : null;
            $username = ($list['status'] == 'true') ? $list['member']['gameuser'] : null;
            $message = $list['message'];

            TmpBankAuto::where('state_id', $id)->update(['auto_response_at' => $datetime, 'auto_response' => $json, 'auto_response_status' => $list['status'], 'auto_response_order' => $order_id, 'auto_response_username' => $username, 'auto_response_message' => $message]);

            // Update Auto
            $auto_created = ($list['status'] == 'true') ? 1 : 0;
            BankStatement::where('state_id', $id)->update(['auto_action' => 1, 'auto_created' => $auto_created, 'auto_order_id' => $order_id, 'auto_username' => $username, 'auto_message' => $message]);

            $textnoti[$id] .= "\r\nAuto Message: ".$list['message'];

            line_notify($textnoti[$id], 'bn7nRooS9OnFcKJmQMVt8cWLPZVHuKewCdWPX6dckD7');

            /**
             * Noti Created Only
            */
            if($auto_created == 1){
                $textnoti[$id] .= "\r\n\r\nOrder ID: ".$list['workspace']['id'];
                $textnoti[$id] .= "\r\nUsername: ".$list['member']['gameuser'];

                line_notify($textnoti[$id], 'rRELZivEJHo64q0A75AdVHlGi8OzVilzFrcAO38Q0yN');
            }
        }

        return compact('arrData', 'response');

    }

    public function postAuto($data)
    {

        $this->entityClass = TmpBankAutoRequest::class;

        $post_data = json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

        $repository = $this->getRepository();

        $arrData = [
            'request' => $post_data
        ];
        $entity = $repository->createEntity($arrData, \App::make(TmpBankAutoRequest::class));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://admin.wpnadmin.com/v1/account_transaction/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json'
        ));
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.103 Safari/537.36');
        $data = curl_exec($ch);

        $input = array(
            'response' => $data,
        );
        $repository->updateEntity($input, $entity);

        return $data;

    }

    public function getList(){

        $lists = TmpBankAuto::getListForAdmin();

        $this->total = count($lists);

        $arrData = [];

        foreach ($lists as $list){

            // Update Status Already Check
            TmpBankAuto::where('id', $list['id'])->update(['auto_status' => 1, 'auto_checked_at' => date('Y-m-d H:i:s')]);

            // Check if state_date > today is error
            if($list['state_date'] > date('Y-m-d H:i:s')){
                continue;
            }

            $bank_no = "";
            $bank_acc = "";
            $name_ref = 0;
            // Kbank
            if($list['bank_id'] == 2){
                $bank_id = 1;
                $bank_no = str_replace(['x','-'], ['',''], $list['state_account_no']);
            }
            // SCB
            elseif($list['bank_id'] == 6){
                $bank_id = 3;

                if(!empty($list['state_account_no'])){
                    $bank_no = $list['state_account_no'];
                    $name_ref = 1;
                }else {
                    $text = str_replace(array('นาย ', 'นางสาว ', 'น.ส. '), array('', '', ''), $list['state_detail']);
                    $tmp = preg_match('/x([0-9]{4}) (.*) (.*)/', $text, $arr);
                    if (!$tmp) {
                        $tmp = preg_match('/X([0-9]{6})/', $text, $arr);
                    }
                    $list['arr_match_status'] = $tmp;
                    $list['arr_match'] = $arr;

                    $list['state_account_no'] = $list['state_detail'];

                    $bank_no = (isset($arr[1])) ? $arr[1] : "";
                    $bank_acc = (isset($arr[2])) ? $arr[2] : "";
                }
            }
            // KTB
            elseif($list['bank_id'] == 3){
                $bank_id = 4;
                $tmp = preg_match('/Future Amount/', $list['state_detail'], $arr);
                if(!$tmp){
                    $tmp = preg_match('/([0-9]{10})/', $list['state_detail'], $arr);
                }
                $list['arr_match_status'] = $tmp;
                $list['arr_match'] = $arr;

                $list['state_account_no'] = $list['state_detail'];

                $bank_no = (isset($arr[1])) ? $arr[1] : "";
            }

            $list['m_bank_id'] = $bank_id;
            $list['bank_no'] = $bank_no;

            if(empty($bank_no)){
                continue;
            }

            // Check match with member bank account
            $member_bank = Members::when($bank_id, function ($query) use ($bank_id, $bank_no, $bank_acc, $name_ref){
                if($bank_id == 3){ // For SCB
                    if($name_ref == 0) {
                        $query->where('withdraw_bank', $bank_id)->where('withdraw_ac', 'like', '%' . $bank_no);
                        // Check with name match
                        if (!empty($bank_acc)) {
                            $query->where('withdraw_name', 'like', '%' . $bank_acc . '%');
                        }
                    }else{
                        $query->where('name_ref', $bank_no);
                    }
                }
                elseif($bank_id == 1){ // For Kbank
                    $query->where('withdraw_bank', $bank_id)->where('withdraw_ac', 'like', '___'.$bank_no.'%');
                }
                else{
                    $query->where('withdraw_bank', $bank_id)->where('withdraw_ac', 'like', '%'.$bank_no.'%');
                }
            })
            // ->where('domain', 4) // sbobetsh
            ->select('A_I', 'new_id', 'username', 'domain', 'status', 'withdraw_ac', 'withdraw_name', 'vip', 'level', 'deposit_bank_set')
            ->get();

            $query_filter = compact('bank_id', 'bank_no', 'bank_acc');

            // Update Status Already Check
            $member_data = (count($member_bank) > 0) ? json_encode($member_bank->toArray(), JSON_UNESCAPED_UNICODE) : null;
            TmpBankAuto::where('id', $list['id'])->update(['auto_member_data' => $member_data, 'query_check' => json_encode($query_filter, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)]);

            $list['member'] = $member_data;

            $ck_notsame = 0;
            $ck_bank = "";
            $ck_domain = [];
            foreach ($member_bank as $key => $bank){
                if($ck_bank != $bank->withdraw_ac && $key > 0){
                    $ck_notsame++;
                }
                $ck_bank = $bank->withdraw_ac;

                /**
                 * Check deposit group bank each domain
                 */
                $bank_dp = BanksSystem::leftJoin('bank_all', 'bank_system.bank_id', '=', 'bank_all.id')
                    ->where('bank_system.domain', $bank->domain)
                    ->where('bank_system.account_number', $list['bank_number'])
                    ->where('bank_system.vip', $bank->vip)
                    ->where('bank_system.level', $bank->level)
                    ->where('bank_system.deposit_bank_set', $bank->deposit_bank_set)
                    ->where('bank_system.bank_id', $bank_id)
                    ->select(
                        'bank_system.*',
                        'bank_all.name'
                    )
                    ->first();
                if(!empty($bank_dp)){
                    $ck_domain[] = $bank;
                }

            }
            $list['ck_notsame'] = $ck_notsame;
            $list['ck_domain'] = $ck_domain;

            // do auto only one match
//            if(count($member_bank) > 0 && $ck_notsame == 0) {
//
//                $list['match_data'] = [
//                    'member' => ($member_bank) ? $member_bank->toArray() : []
//                ];
//                $arrData[] = $list;
//
//            }

            if(count($ck_domain) > 0 && $ck_notsame == 0) {

                $list['match_data'] = [
                    'member' => $ck_domain
                ];
                $arrData[] = $list;

            }

            // $arrData[] = $list;

        }

        return $arrData;

    }

    public function indexTest($id)
    {

        $lists = $this->getListTest($id);

        return $lists;
    }

    public function getListTest($id){

        $lists = TmpBankAuto::getListForAdminTest($id);

        $this->total = count($lists);

        $arrData = [];

        foreach ($lists as $list){

            // Check if state_date > today is error
            if($list['state_date'] > date('Y-m-d H:i:s')){
                continue;
            }

            $bank_no = "";
            $bank_acc = "";
            $name_ref = 0;
            // Kbank
            if($list['bank_id'] == 2){
                $bank_id = 1;
                $bank_no = str_replace(['x','-'], ['',''], $list['state_account_no']);
            }
            // SCB
            elseif($list['bank_id'] == 6){
                $bank_id = 3;
                if(!empty($list['state_account_no'])){
                    $bank_no = $list['state_account_no'];
                    $name_ref = 1;
                }else {
                    $text = str_replace(array('นาย ', 'นางสาว ', 'น.ส. '), array('', '', ''), $list['state_detail']);
                    $tmp = preg_match('/x([0-9]{4}) (.*) (.*)/', $text, $arr);
                    if (!$tmp) {
                        $tmp = preg_match('/X([0-9]{6})/', $text, $arr);
                    }
                    $list['arr_match_status'] = $tmp;
                    $list['arr_match'] = $arr;

                    $list['state_account_no'] = $list['state_detail'];

                    $bank_no = (isset($arr[1])) ? $arr[1] : "";
                    $bank_acc = (isset($arr[2])) ? $arr[2] : "";
                }
            }
            // KTB
            elseif($list['bank_id'] == 3){
                $bank_id = 4;
                $tmp = preg_match('/Future Amount/', $list['state_detail'], $arr);
                if(!$tmp){
                    $tmp = preg_match('/([0-9]{10})/', $list['state_detail'], $arr);
                }
                $list['arr_match_status'] = $tmp;
                $list['arr_match'] = $arr;

                $list['state_account_no'] = $list['state_detail'];

                $bank_no = (isset($arr[1])) ? $arr[1] : "";
            }

            $list['m_bank_id'] = $bank_id;
            $list['bank_no'] = $bank_no;
            $list['name_ref'] = $name_ref;

            if(empty($bank_no)){
                continue;
            }

            // Check match with member bank account
            $member_bank = Members::when($bank_id, function ($query) use ($bank_id, $bank_no, $bank_acc, $name_ref){
                if($bank_id == 3){ // For SCB
                    if($name_ref == 0) {
                        $query->where('withdraw_bank', $bank_id)->where('withdraw_ac', 'like', '%' . $bank_no);
                        // Check with name match
                        if (!empty($bank_acc)) {
                            $query->where('withdraw_name', 'like', '%' . $bank_acc . '%');
                        }
                    }else{
                        $query->where('name_ref', $bank_no);
                    }
                }
                elseif($bank_id == 1){ // For Kbank
                    $query->where('withdraw_bank', $bank_id)->where('withdraw_ac', 'like', '___'.$bank_no.'%');
                }
                else{
                    $query->where('withdraw_bank', $bank_id)->where('withdraw_ac', 'like', '%'.$bank_no.'%');
                }
            })
            // ->where('domain', 4) // sbobetsh
            ->select('A_I', 'new_id', 'id', 'username', 'domain', 'status', 'withdraw_bank', 'withdraw_ac', 'withdraw_name', 'vip', 'level', 'deposit_bank_set')
            ->get();

            return $query_filter = compact('bank_id', 'bank_no', 'bank_acc', 'member_bank');

            // Update Status Already Check
            $member_data = (count($member_bank) > 0) ? json_encode($member_bank->toArray(), JSON_UNESCAPED_UNICODE) : null;

            $list['member'] = $member_data;

            $ck_notsame = 0;
            $ck_bank = "";
            $ck_domain = [];
            foreach ($member_bank as $key => $bank){
                if($ck_bank != $bank->withdraw_ac && $key > 0){
                    $ck_notsame++;
                }
                $ck_bank = $bank->withdraw_ac;

                /**
                 * Check deposit group bank each domain
                */
                $bank_dp = BanksSystem::leftJoin('bank_all', 'bank_system.bank_id', '=', 'bank_all.id')
                    ->where('bank_system.domain', $bank->domain)
                    ->where('bank_system.account_number', $list['bank_number'])
                    ->where('bank_system.vip', $bank->vip)
                    ->where('bank_system.level', $bank->level)
                    ->where('bank_system.deposit_bank_set', $bank->deposit_bank_set)
                    ->where('bank_system.bank_id', $bank_id)
                    ->select(
                        'bank_system.*',
                        'bank_all.name'
                    )
                    ->first();
                if(!empty($bank_dp)){
                    $ck_domain[] = $bank;
                }

            }
            $list['ck_notsame'] = $ck_notsame;
            $list['ck_domain'] = $ck_domain;

            // do auto only one match
//            if(count($member_bank) > 0 && $ck_notsame == 0) {
//                $list['match_data'] = [
//                    'member' => ($member_bank) ? $member_bank->toArray() : []
//                ];
//                $arrData[] = $list;
//            }
            if(count($ck_domain) > 0 && $ck_notsame == 0) {
                $list['match_data'] = [
                    'member' => $ck_domain
                ];
                $arrData[] = $list;
            }

            // $arrData[] = $list;

        }

        return $arrData;

    }
}