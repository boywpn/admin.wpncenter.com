<?php

namespace App\Http\Controllers\Games\Tiger;

use Illuminate\Http\Request;

class MemberController extends TigerController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function create(Request $request)
    {
        $data = $request->input();

        return $this->saveUsername($data);
    }

    public function saveUsername($data = null)
    {
        $setParam = [
            'codetext' => $data['username'],
            'pws' => $data['password'],
            'name' => '',
            'phone' => '',
            'account' => '',
            'account_name' => '',
            'bank' => '5',
            'water' => '0',
            'drawback' => '0',
            'min_price' => '20',
            'max_price' => '150000',
            'max_match' => '500000',
            'credit' => '0',
            'commission' => '0.00%',
            'commission_array' => '0.0',
            //'share_percent_array' => '',
            'keep_array' => '0',
            'keep_array_live' => '0',
            //'share_percent' => '',
            //'effective_keep' => '',
            //'bet' => 'on',
            //'status_keep' => '0',
            //'status_limit_keep' => '0',
            //'limit_keep_array' => '0',
            'type_member' => '2',
            //'type_web' => '',
            'min_price_mx' => '20',
            'max_price_mx' => '150000',
            'max_match_mx' => '500000',
            //'share_percent_array_mx' => '',
            'keep_array_mx' => '0',
            //'share_percent_mx' => '',
            //'effective_keep_mx' => '0',
            //'bet_mx' => 'on',
            //'limit_keep_array_mx' => '',
            'commission_array_mx_2' => '0.00',
            'commission_array_mx_3' => '0.00',
            'commission_array_mx_4' => '0.00',
            'commission_array_mx_5_6' => '0.00',
            'commission_array_mx_7_9' => '0.00',
            'commission_array_mx_10' => '0.00',

            //'share_percent_array_cs' => '0',
            'keep_array_cs' => '0',
            //'share_percent_cs' => '.00',
            //'effective_keep_cs' => '0',
            'bet_cs' => '1',
            //'limit_keep_array_cs' => '0',
            'commission_array_cs' => '0.00',

            //'share_percent_array_cssa' => '0',
            'keep_array_cssa' => '0',
            //'share_percent_cssa' => '.00',
            //'effective_keep_cssa' => '0',
            'bet_cssa' => '1',
            //'limit_keep_array_cssa' => '0',
            'commission_array_cssa' => '0.00',

            //'share_percent_array_cspg' => '0',
            'keep_array_cspg' => '0',
            //'share_percent_cspg' => '.00',
            //'effective_keep_cspg' => '0',
            'bet_cspg' => '1',
            //'limit_keep_array_cspg' => '0',
            'commission_array_cspg' => '0.00',

            //'share_percent_array_csawc' => '0',
            'keep_array_csawc' => '0',
            //'share_percent_csawc' => '.00',
            //'effective_keep_csawc' => '0',
            'bet_csawc' => '1',
            //'limit_keep_array_csawc' => '',
            'commission_array_csawc' => '0.00'
        ];

        $param = $this->setParam($setParam, 'create_user.php');

        $response = $this->push();
        $response = json_decode($response, true);

        return $response;
    }

    public function actionPost($data, $url)
    {

        $param = $this->setParam($data, $url);

        $response = $this->push();
        $response = json_decode($response, true);

        return $response;
    }

    public function actionGet($data, $url, $prefix = null, $suffix = null, $debug = false)
    {

        $param = $this->setParam($data, $url, $prefix, $suffix);

        $response = $this->push(false);
        $response = json_decode($response, true);

        if($debug) {
            return compact('param', 'response');
        }

        return $response;
    }

}
