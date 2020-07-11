<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\AppController;
use App\Models\Old\Domains;
use App\Models\Old\Members;
use App\Models\Old\Username;
use Illuminate\Http\Request;
use Modules\Core\Partners\Entities\Partners;

class MarketingController extends AppController
{

    public function searchMembers(Request $request){

        $get = $request->input();

        $data = [];

        $domains = Domains::where('status', 1)->where('main', 1)->get();

        $member = [];

        if(isset($get['user'])) {

            $user = Username::where('domain', $get['domain'])->where('username', $get['user'])->first();

            if($user) {
                $member = Members::where('domain', $get['domain'])
                    ->with(['order' => function ($query) {
                        $query->select('id', 'member_id', 'datetime', 'money')->orderBy('A_I', 'ASC')->limit(1);
                    }])
                    ->with(['membersAgent' => function ($query) {
                        $query->select('id', 'reference', 'domain', 'name');
                    }])
                    ->where('id', $user->member_id)
                    ->select('A_I', 'id', 'withdraw_name', 'username', 'created', 'agent')
                    ->get();
            }
        }

//        if(isset($get['phone'])) {
//            $member = Members::where('domain', $get['domain'])
//                ->with(['order' => function($query){
//                    $query->select('id', 'member_id', 'datetime', 'money')->orderBy('A_I', 'ASC')->limit(1);
//                }])
//                ->where('username', $get['phone'])
//                ->select('A_I', 'id', 'withdraw_name', 'username', 'created')
//                ->get();
//        }

        $data = [
            'get' => $get,
            'domains' => $domains,
            'member' => $member
        ];

//        return $data;

        return view('layouts.marketing', $data);

    }

}
