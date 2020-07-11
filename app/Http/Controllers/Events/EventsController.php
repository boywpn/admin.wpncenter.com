<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\AppController;
use App\Models\Old\Domains;
use App\Models\Old\Games;
use App\Models\Old\Members;
use App\Models\Old\Username;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Modules\Api\Http\Controllers\Game\TransferApiController;
use Modules\Core\Partners\Entities\Partners;
use Modules\Core\Username\Http\Controllers\UsernameController;
use Modules\Platform\User\Entities\User;

class EventsController extends AppController
{

    public function __construct(Request $request)
    {

        $domains = Domains::where('status', 1)->where('main', 1)->get();
        $param = $request->input();

        View::share('domains', $domains);
        View::share('param', $param);
    }

    public function freeDemo200(Request $request){

        $param = $request->input();

        $members = [];
        $member_user = [];

        if(isset($param['user']) || isset($param['phone'])) {

            $members = Members::where('member.domain', $param['domain'])
                ->with(['order' => function ($query) {
                    $query->select('id', 'member_id', 'datetime', 'money')->orderBy('A_I', 'ASC')->limit(1);
                }])
                ->with(['membersAgent' => function ($query) {
                    $query->select('id', 'reference', 'domain', 'name');
                }])
                ->with(['username' => function ($query) {
                    $query->select('A_I', 'new_id', 'member_id', 'username', 'created', 'web_name', 'game_web');
                }])
                ->when($param, function($query) use ($param){
                    if(!empty($param['user']) && empty($param['phone'])){
                        $query->leftJoin('game_user', 'game_user.member_id', '=', 'member.id')
                            ->where('game_user.username', $param['user']);
                    }
                    elseif(empty($param['user']) && !empty($param['phone'])){
                        $query->where('member.username', $param['phone']);
                    }
                    else{
                        $query->leftJoin('game_user', 'game_user.member_id', '=', 'member.id')
                            ->where('game_user.username', $param['user'])
                            ->where('member.username', $param['phone']);
                    }
                })
                // ->where('member.username', $param['phone'])
                ->select(
                    'member.A_I',
                    'member.id',
                    'member.withdraw_name',
                    'member.username AS m_username',
                    'member.created',
                    'member.agent')
                ->first();

            if($members){
                $members = $members->toArray();

                foreach ($members['username'] as $user){
                    $new_u = \Modules\Core\Username\Entities\Username::where('id', $user['new_id'])->select('id', 'username', 'have_promo', 'promo_at', 'promo_code')->first();
                    $user['new'] = $new_u->toArray();
                    $member_user[$user['game_web']] = $user;
                }
            }
        }

//        $game_web = Games::where('status', 1)
//            ->leftJoin('game_user', 'game_user.game_web' , '=', 'game_web.id')
//            ->orderBy('game_web.sort')
//            ->select(
//                'game_web.id',
//                'game_web.new_id',
//                'game_web.web_code',
//                'game_web.name',
//                'game_user.A_I',
//                'game_user.new_id AS g_new_id',
//                'game_user.username AS g_username',
//                'game_user.created AS u_created',
//                'game_user.web_name'
//            )
//            ->where('member_id', $member['id'])
//            ->get();

        $game_web = Games::where('status', 1)
            ->where('login_api', 1)
            ->orderBy('game_web.sort')
            ->select(
                'game_web.id',
                'game_web.new_id',
                'game_web.web_code',
                'game_web.name'
            )
            ->get();

//        return $members;

        $show_table = true;
        $text_warning = null;
        $text_danger = null;
        if(isset($param['event'])){
            if($param['event'] == "demo200"){
                $text_warning = '<div class="alert alert-warning"><b>เงื่อนไขโปรโมชั่น!</b> โปรโมชั่นนี้สำหรับ ลูกค้าที่สมัครใหม่เท่านั้น ไม่เคยมีรายการฝากในระบบ <b>สามารถสังเกตุได้จาก รายการสมัคร ถ้ามีข้อมูลการทำรายการขึ้น หมายถึงลูกค้าเคยมีรายการแจ้งฝากกับทางเว็บแล้ว ไม่สามารถระบโปรโมชั่นนี้ได้</b></div>';

                if($members) {
                    if (count($members['order']) > 1) {
                        $text_danger = '<div class="alert alert-danger">ลูกค้าไม่สามารถรับโปรโมชั่นนี้ได้ เนื่องจากมีรายการฝากแล้วในระบบ</div>';
                        $show_table = false;
                    }
                }
            }
        }

        $data = compact('members', 'game_web', 'member_user', 'show_table', 'text_warning', 'text_danger');

//        return $data;

        return view('events.demo200', $data);

    }

    public function genUsername(Request $request){

        $data = $request->all();

        $user = new UsernameController();
        $username = $user->genUsernameWithOld($data['id'], $data['game_id']);

        return $username;

    }

    public function transferForm(Request $request){

        $get = $request->all();

        $username = \Modules\Core\Username\Entities\Username::findOrFail($get['username_id']);

        $data = [
            'get' => $get,
            'username' => $username
        ];

        return view('events.transfer-form', $data);

    }

    public function transferCredit(Request $request){

        $get = $request->all();

        // check username is created on trader
        $username = \Modules\Core\Username\Entities\Username::findOrFail($get['id']);
        // check member have promotion
        $member = \Modules\Member\Members\Entities\Members::findOrFail($username->member_id);
        if($get['type'] == 1) {
            if ($username->have_promo == 1) {
                return response()->json(['status' => false, 'msg' => 'Username ไม่สามารถรับโปรโมชั่นซ้ำได้!']);
            }
            if ($member->have_promo == 1) {
                return response()->json(['status' => false, 'msg' => 'Member ไม่สามารถรับโปรโมชั่นซ้ำได้!']);
            }
            // create username on trader
            if ($username->is_created == 0) {
                $userApp = new UsernameController();
                $adduser = $userApp->pushUsernameApi($get['id']);
                if (!$adduser['status']) {
                    return $adduser;
                }
            }
        }

        $amount = $get['amount'];
        $from = 'admin_events';

        if($get['type'] == 2){
            if($get['amount'] == 0){
                $arrTrans = [
                    'action' => 'transfer',
                    'orderid' => $get['order_code'],
                    'custid' => $get['username'],
                    'type' => 'detail',
                    'amount' => $amount,
                    'staffid' => 1,
                    'from' => $from,
                    'stateid' => '333333',
                    'local_ip' => get_client_ip(),
                    'auto' => true,
                ];
                $trans = new TransferApiController();
                $detail = $trans->transfer($arrTrans);
                if($detail['responseStatus']['code'] != 200){
                    return $detail;
                }
                $amount = $detail['responseDetails']['netbanlance'];

                if($amount == 0){
                    // Update Status have promotion
                    $arrUpdate = ['have_promo' => 0, 'promo_code' => $get['order_code'], 'promo_at' => date('Y-m-d H:i:s')];
                    \Modules\Core\Username\Entities\Username::where(['id' => $get['id']])->update($arrUpdate);

                    \Modules\Member\Members\Entities\Members::where(['id' => $username->member_id])->update($arrUpdate);

                    return response()->json(['status' => true, 'msg' => 'เรียบร้อย!', 'data' => $detail]);
                }

                $amount = $amount * -1;
                $from = 'admin_cancel_events';
            }else{
                return response()->json(['status' => false, 'msg' => 'ต้องถอนเงินออกทั้งหมดเท่านั้น!']);
            }
        }

        $arrTrans = [
            'action' => 'transfer',
            'orderid' => $get['order_code'],
            'custid' => $get['username'],
            'type' => 'transfer',
            'amount' => $amount,
            'staffid' => 1,
            'from' => $from,
            'stateid' => '333333',
            'local_ip' => get_client_ip(),
            'auto' => true,
        ];
        $trans = new TransferApiController();
        $response = $trans->transfer($arrTrans);

        if($response['responseStatus']['code'] == 200){
            if($get['type'] == 1) {
                // Update Status have promotion
                $arrUpdate = ['have_promo' => 1, 'promo_code' => $get['order_code'], 'promo_at' => date('Y-m-d H:i:s')];
                \Modules\Core\Username\Entities\Username::where(['id' => $get['id']])->update($arrUpdate);

                \Modules\Member\Members\Entities\Members::where(['id' => $username->member_id])->update($arrUpdate);
            }
            if($get['type'] == 2) {
                // Update Status have promotion
                $arrUpdate = ['have_promo' => 0, 'promo_code' => $get['order_code'], 'promo_at' => date('Y-m-d H:i:s')];
                \Modules\Core\Username\Entities\Username::where(['id' => $get['id']])->update($arrUpdate);

                \Modules\Member\Members\Entities\Members::where(['id' => $username->member_id])->update($arrUpdate);
            }
        }

        return $response;

    }

}
