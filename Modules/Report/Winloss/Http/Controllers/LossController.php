<?php

namespace Modules\Report\Winloss\Http\Controllers;

use App\Models\TransfersLog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Api\Http\Controllers\Game\TransferApiController;
use Modules\Core\Boards\Entities\Boards;
use Modules\Core\Username\Entities\Username;
use Modules\Member\Members\Entities\Members;

class LossController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index($game = null)
    {

        $request = \App::make(Request::class);
        $input = $request->all();

        $date = date('Y-m-d');

        $arrDate = [];
        for($i = 1; $i <= 7; $i++){
            $arrDate[] = date('Y-m-d', strtotime('-'.$i.' days', strtotime($date)));
        }

        $arrMix = [];
        // $winloss = [];
        foreach ($arrDate as $date) {
            $formData = [];
            $formData['from'] = $date;
            $formData['to'] = $date;
            $formData['from_time'] = '00:00:00';
            $formData['to_time'] = '23:59:59';

            $winloss = Boards::getBoardMemberByGame($formData, $game);

            $arrMix = array_merge($arrMix, $winloss);
        }

        $data['date'] = $arrDate;

        // Loop
        $arrNew = [];
        foreach ($arrMix as $mix){
            $username = Username::findOrFail($mix->username_id);
            $member = Members::findOrFail($mix->member_id);
            $arrNew[$mix->username_id]['username'] = $username->username;
            $arrNew[$mix->username_id]['member_id'] = $mix->member_id;
            $arrNew[$mix->username_id]['name'] = $member->name;
            $arrNew[$mix->username_id]['date'][$mix->work_date][] = $mix;
        }

        $data['lists'] = $arrNew;

        /**
         * Create Json
        */
        $total_loss = 0;
        $total_back_credit = 0;
        $count_back = 0;
        $total_winloss = 0;
        $arrBack = [];
        foreach($arrNew as $id => $list){

            $total_member_winloss = 0;
            $chk_all = 1;
            $credit = 0;

            foreach($arrDate AS $d){
                $winloss = (isset($list['date'][$d][0]->member_winloss)) ? $list['date'][$d][0]->member_winloss : "";

                if(!empty($winloss)){
                    $total_member_winloss += $winloss;
                    $total_winloss += $winloss;
                }else{
                    $chk_all = 0;
                }
            }

            if($total_member_winloss > 0){
                $chk_all = 0;
            }else{
                $credit = ($total_member_winloss * 0.05) * -1;

                if($chk_all){
                    $total_loss += $total_member_winloss;
                    $total_back_credit += $credit;
                    $count_back += 1;

                    $arrBack[$id] = [
                        'id' => $id,
                        'member_id' => $list['member_id'],
                        'username' => $list['username'],
                        'amount' => $credit
                    ];
                }
            }

        }

        // return $arrBack;

        return view('report/winloss::loss', $data);

    }

    public function pushBackAuto($game = null, $act = 'all')
    {

        $request = \App::make(Request::class);
        $input = $request->all();

        $trans = new TransferApiController();

//        $arrDate = [
//            '2019-12-16',
//            '2019-12-17',
//            '2019-12-18',
//            '2019-12-19',
//            '2019-12-20',
//            '2019-12-21',
//            '2019-12-22',
//        ];

        $date = date('Y-m-d');

        $arrDate = [];
        for($i = 1; $i <= 7; $i++){
            $arrDate[] = date('Y-m-d', strtotime('-'.$i.' days', strtotime($date)));
        }

        $arrMix = [];
        // $winloss = [];
        foreach ($arrDate as $date) {
            $formData = [];
            $formData['from'] = $date;
            $formData['to'] = $date;
            $formData['from_time'] = '00:00:00';
            $formData['to_time'] = '23:59:59';

            $winloss = Boards::getBoardMemberByGame($formData, $game);

            $arrMix = array_merge($arrMix, $winloss);
        }

        $data['date'] = $arrDate;

        // Loop
        $arrNew = [];
        foreach ($arrMix as $mix){
            $username = Username::findOrFail($mix->username_id);
            $member = Members::findOrFail($mix->member_id);
            $arrNew[$mix->username_id]['username'] = $username->username;
            $arrNew[$mix->username_id]['member_id'] = $mix->member_id;
            $arrNew[$mix->username_id]['name'] = $member->name;
            $arrNew[$mix->username_id]['date'][$mix->work_date][] = $mix;
        }

        $data['lists'] = $arrNew;

        /**
         * Create Json
         */
        $total_loss = 0;
        $total_back_credit = 0;
        $count_back = 0;
        $count_other = 0;
        $total_winloss = 0;
        $arrBack = [];
        $arrOther = [];
        $arrTrans = [];
        foreach($arrNew as $id => $list){

            $total_member_winloss = 0;
            $chk_all = 1;
            $credit = 0;

            // Check Old Member
            $member = Members::findOrFail($list['member_id']);

            foreach($arrDate AS $d){
                $winloss = (isset($list['date'][$d][0]->member_winloss)) ? $list['date'][$d][0]->member_winloss : "";

                if(!empty($winloss)){
                    $total_member_winloss += $winloss;
                    $total_winloss += $winloss;
                }else{
                    $chk_all = 0;
                }
            }

            if($total_member_winloss > 0){ // Check Loss Only
                $chk_all = 0;
            }else{
                $credit = ($total_member_winloss * 0.05) * -1;

                if($chk_all){
                    $total_loss += $total_member_winloss;
                    $total_back_credit += $credit;
                    $count_back += 1;

                    // Check Exist
                    $ck_trans = TransfersLog::where('username', $list['username'])
                        ->where('order_code', 'loss_auto')
                        ->whereRaw('DATE(request_time) = ?', [date('Y-m-d')])
                        ->count();

                    // Transfer if not exist
                    $response = null;
                    if($ck_trans == 0) {
                        $arrTrans = [
                            'comm_id' => time(),
                            'action' => 'transfer',
                            'orderid' => 'loss_auto',
                            'custid' => $list['username'],
                            'type' => 'transfer',
                            'amount' => $credit,
                            'staffid' => 1,
                            'from' => 'api_auto_lossback',
                            'stateid' => '444444',
                            'local_ip' => get_client_ip(),
                            'auto' => true,
                        ];

                        $response = $trans->transfer($arrTrans);
                    }


                    $arrBack[$id] = [
                        'id' => $id,
                        'member_id' => $list['member_id'],
                        'member_name' => $member->name,
                        'old_member_id' => $member->old_id,
                        'username' => $list['username'],
                        'loss' => $total_member_winloss,
                        'amount' => $credit,
                        'tranParam' => $arrTrans,
                        'response' => $response,
                        'exist' => $ck_trans,
                    ];

                }else{
                    $arrOther[$id] = [
                        'id' => $id,
                        'member_id' => $list['member_id'],
                        'member_name' => $member->name,
                        'old_member_id' => $member->old_id,
                        'username' => $list['username'],
                        'loss' => $total_member_winloss,
                        'amount' => $credit
                    ];
                }
            }

        }

        if($act == 'all') {
            $return = [
                'total' => count($arrNew),
                'total_match' => count($arrBack),
                'datetime' => date('Y-m-d H:i:s'),
                'lists' => $arrBack
            ];
        }else{
            $return = [
                'total' => count($arrNew),
                'total_match' => count($arrOther),
                'datetime' => date('Y-m-d H:i:s'),
                'lists' => $arrOther
            ];
        }

        return $return;

    }

}
