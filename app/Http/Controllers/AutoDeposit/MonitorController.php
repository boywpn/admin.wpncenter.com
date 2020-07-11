<?php
namespace App\Http\Controllers\AutoDeposit;

use App\Http\Controllers\AppController;
use App\Http\Controllers\Controller;
use App\Models\Old\Members;
use App\Models\Old\OrdersLog;
use App\Models\Trnf\BankStatement;
use App\Models\Trnf\TmpBankAutoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Api\Http\Controllers\Upc\V1\WalletController;
use Modules\Member\Members\Entities\Members AS NewMember;
use App\Models\Trnf\TmpBankAuto;
use Modules\Member\Members\Http\Controllers\MembersController;
use Modules\Wallet\Jobs\Entities\Jobs;

class MonitorController extends AppController
{

    public function monitor(){

        return view('autodp.monitor');

    }

    public function matchName(){

        $state = BankStatement::where('bank_id', 6)
            ->with(['transfer' => function($query){
                $query->select('tran_id', 'tran_amount', 'order_id');
            }])
            ->where('state_deposit', '>', 0)
            ->where('match_name', 0)
            // ->where(DB::raw("order_id != '' OR state_account_no != '0'"))
            // ->whereRaw('order_id' != '' OR 'state_account_no' != 0)
            ->whereNotNull('state_account_no')
            ->orderBy('state_date', 'DESC')
            ->select(
                'state_id',
                'state_date',
                'state_deposit',
                'bank_id',
                'state_account_no',
                'order_id',
                'state_tran_id'
            )
            ->limit(100)
            ->get();

        $arrDataNull = [];
        $arrData = [];
        foreach ($state AS $items){

            $item = $items->toArray();

//            if($item['order_id'] == "" && $item['state_tran_id'] == 0){
//                continue;
//            }
//
//            if($item['transfer']['order_id'] == 0){
//                continue;
//            }

            $order_id = $item['order_id'];
            if($item['order_id'] == ""){
                $order_id = $item['transfer']['order_id'];
            }

            $order = [];

            if(!empty($order_id)) {

                BankStatement::where(['state_id' => $item['state_id']])->update(['match_name' => 1]);

                $order = OrdersLog::where('id', $order_id)
                    ->with(['member' => function ($query) {
                        $query->select('A_I', 'id', 'username', 'withdraw_name', 'withdraw_bank', 'withdraw_ac');
                    }])
                    ->select('id', 'username', 'member_id', 'money')
                    ->first();

                if (!$order) {
                    continue;
                }

                $order = $order->toArray();

                Members::where(['A_I' => $order['member']['A_I']])->update(['name_ref' => $item['state_account_no']]);

                // Get Member
                $member = Members::where(['A_I' => $order['member']['A_I']])->first();
                // Update Ref to new member bank
                \Modules\Member\Members\Entities\Members::where('bank_number', $member->withdraw_ac)->update(['bank_ref' => $item['state_account_no']]);

                $arrData[] = [
                    'item' => $item,
                    'order_id' => $order_id,
                    'order' => $order,
                ];
            }else{

                $arrDataNull[] = [
                    'item' => $item,
                    'order_id' => $order_id,
                    'order' => $order,
                ];

            }

        }

        $count = [
            'have' => count($arrData),
            'nohave' => count($arrDataNull)
        ];

        return compact('count', 'arrData', 'arrDataNull');

    }

}