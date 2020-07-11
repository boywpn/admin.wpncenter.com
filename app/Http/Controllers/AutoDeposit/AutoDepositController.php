<?php
namespace App\Http\Controllers\AutoDeposit;

use App\Http\Controllers\AppController;
use App\Http\Controllers\Controller;
use App\Models\Old\Members;
use App\Models\Trnf\BankStatement;
use Illuminate\Http\Request;
use Modules\Api\Http\Controllers\Upc\V1\WalletController;
use Modules\Member\Members\Entities\Members AS NewMember;
use App\Models\Trnf\TmpBankAuto;
use Modules\Member\Members\Http\Controllers\MembersController;
use Modules\Wallet\Jobs\Entities\Jobs;

class AutoDepositController extends AppController
{

    protected $lists;

    public function index()
    {
        $this->lists = $this->getList();

        // Transfer Process
        $this->doTransfer();
    }

    public function test($id)
    {
        return $this->lists = $this->getList($id);

        // Transfer Process
//        $this->doTransfer();
    }

    public function createByStatement(Request $request){

        $input = $request->all();

        $this->lists = $this->getList($input['statement']);

        // Transfer Process
        $this->doTransfer();

    }

    public function doTransfer(){

        $lists = $this->lists;

        if(count($lists) == 0){
            return "";
        }
        $arrData = [];
        // Created Member on new system
        $base = new MembersController();
        $wallet = new WalletController();

        foreach($lists as $list){

            $data = $list['match_data']['member'][0];
            $amount = $list['state_deposit'];
            $tmp_id = $list['id'];
            $state_id = $list['state_id'];
            $statement_id = $list['state_id'];
            $statement_created = $list['state_date'];
            $member_id = $data['A_I'];

            $member = $base->saveMember($member_id);

            $member_id = $member['data']['id']; // Reset member_id to use new system
            $member_username = $member['data']['username']; // Reset member_id to use new system

            // Generate OneTime Key
            $onetime_key = rand12(16);
            // Update key to member
            NewMember::where('id', $member_id)->update(['onetime_key' => $onetime_key]);

            // Generate Token Onetime
            \cryptor::setKey(env('CRYPTOR_SALT_WALLET'), env('CRYPTOR_KEY_WALLET'));
            $encrypt = \cryptor::encrypt($member_id."_".$onetime_key);
            $token_wallet = $encrypt;
            $token_wallet_en = \cryptor::decrypt($encrypt);

            // Set Data Wallet
            $data_post = [
                'method' => 'credit',
                'type' => 1, // From Statement
                'token' => $token_wallet,
                'member_id' => $member_id,
                'state_id' => $statement_id,
                'created_at' => $statement_created,
                'amount' => $amount,
                'onetime_key' => $onetime_key,
            ];


            $entity = $wallet->saveWallet($data_post, 1);
            // Set entity of wallet to wallet api
            $data_post['wallet_id'] = $entity['id'];
            $data_post['orderId'] = $statement_id;

            // Auto Transfer Wallet
            $transfer = [];
            return $transfer = $wallet->doTransfer($data_post);
            $json = json_encode($transfer, JSON_UNESCAPED_UNICODE);

            // Update Status to Success or Not

            // Update status wallet
            $this->entityClass = Jobs::class;
            $repository = $this->getRepository();
            $entity = $repository->findWithoutFail($entity['id']);

            $datetime = date('Y-m-d H:i:s');
            if($transfer['codeid'] == 0){
                // Update status wallet
                $repository->updateEntity(['transaction_id' => $transfer['data']['id'], 'status_id' => 3, 'response_at' => $datetime, 'response_result' => $json], $entity);

                // Update Status to Success
                TmpBankAuto::where('id', $tmp_id)->update(['status' => 2, 'success_at' => $datetime, 'response_transfer' => $json]);
                // Update Statement Status
                BankStatement::where('state_id', $statement_id)->update(['tmp_id' => $tmp_id, 'wallet_used_at' => $datetime]);

                // Send noti to line group
                $textnoti = "\r\nรายการฝาก: ".$statement_id;
                $textnoti .= "\r\nจำนวน: ".$amount;
                $textnoti .= "\r\nMember ID: ".$member_id;
                $textnoti .= "\r\nMember User: ".$member_username;
                $textnoti .= "\r\nสร้างเมื่อ: ".$datetime;
                $textnoti .= "\r\nเวลาธนาคาร: ".$statement_created;
                // line_notify($textnoti, 'bn7nRooS9OnFcKJmQMVt8cWLPZVHuKewCdWPX6dckD7');

            }else{
                // Update Status to Error
                TmpBankAuto::where('id', $tmp_id)->update(['status' => 3, 'response_transfer_error' => $json]);

                // Update status wallet
                $repository->updateEntity(['status_id' => 4, 'response_at' => $datetime, 'response_result' => $json], $entity);
            }

            $arrData[] = [
                'data' => $data,
                'amount' => $amount,
                'member' => $member,
                'data_post' => $data_post,
                'tranfer' => $transfer,
                'token_wallet' => $token_wallet,
                'token_wallet_en' => $token_wallet_en
            ];
        }

        print_r($arrData);

    }

    public function getList($id = null){

        if(empty($id)) {
            $lists = TmpBankAuto::getList();
        }else{
            $lists = TmpBankAuto::getById($id);
        }

        $arrData = [];

        foreach ($lists as $list){

            // Update Status Already Check
//            TmpBankAuto::where('id', $list['id'])->update(['status' => 1, 'checked_at' => date('Y-m-d H:i:s')]);

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

                $bank_no = (isset($arr[1])) ? $arr[1] : "";
            }

            $list['bank_no'] = $bank_no;

            if(empty($bank_no)){
                continue;
            }

//            // Check match with member bank account
//            $member_bank = Members::where('withdraw_bank', $bank_id)
//                ->where('domain', 4) // sbobetsh
//                ->when($bank_id, function ($query) use ($bank_id, $bank_no, $bank_acc, $name_ref){
//                    if($name_ref == 0) {
//                        $query->where('withdraw_bank', $bank_id)->where('withdraw_ac', 'like', '%' . $bank_no);
//                        // Check with name match
//                        if (!empty($bank_acc)) {
//                            $query->where('withdraw_name', 'like', '%' . $bank_acc . '%');
//                        }
//                    }else{
//                        $query->where('name_ref', $bank_no);
//                    }
//                })
//                ->select('A_I', 'new_id', 'username', 'domain', 'status', 'withdraw_ac', 'withdraw_name')
//                ->get();
//
//            // Update Status Already Check
//            $member_data = (count($member_bank) > 0) ? json_encode($member_bank->toArray(), JSON_UNESCAPED_UNICODE) : null;
//            TmpBankAuto::where('id', $list['id'])->update(['member_data' => $member_data]);
//
//            $ck_notsame = 0;
//            $ck_bank = "";
//            foreach ($member_bank as $key => $bank){
//                if($ck_bank != $bank->withdraw_ac && $key > 0){
//                    $ck_notsame++;
//                }
//                $ck_bank = $bank->withdraw_ac;
//            }
//            $list['ck_notsame'] = $ck_notsame;
//
//            // do auto only one match
//            if(count($member_bank) > 0 && $ck_notsame == 0) {
//
//                $list['match_data'] = [
//                    'member' => ($member_bank) ? $member_bank->toArray() : []
//                ];
//                $arrData[] = $list;
//
//            }

            $arrData[] = $list;

        }

        return $arrData;

    }
}