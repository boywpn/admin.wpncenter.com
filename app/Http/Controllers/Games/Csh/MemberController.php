<?php

namespace App\Http\Controllers\Games\Csh;

use Illuminate\Http\Request;

class MemberController extends CshController
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

    public function saveUsername($data = null, $debug = false)
    {
        $setParam = [
            'username' => $data['username'],
            'password' => $data['password'],
            'name' => '',
            'tel' => '',
            'note' => '',
            'openGames[0]' => 'pg',
            'givePt_pg' => '0',
            'openGames[1]' => 'joker',
            'givePt_joker' => '0',
            'openGames[2]' => 'dg',
            'givePt_dg' => '0',
            'openGames[3]' => 'sa',
            'givePt_sa' => '0',
            'openGames[4]' => 'sexy',
            'givePt_sexy' => '0',
            'openGames[5]' => 'wm',
            'givePt_wm' => '0',
            'openGames[6]' => 'netent',
            'givePt_netent' => '0',
            'openGames[7]' => 'ttg',
            'givePt_ttg' => '0',
            'openGames[8]' => 'pp',
            'givePt_pp' => '0',
            'openGames[9]' => 'sp',
            'givePt_sp' => '0',
            'openGames[10]' => 'ep',
            'givePt_ep' => '0',
            'openGames[11]' => 'ps',
            'givePt_ps' => '0',
            'openGames[12]' => 'qtech',
            'givePt_qtech' => '0',
            'openGames[13]' => 'bg',
            'givePt_bg' => '0',
            'openGames[14]' => 'gamatron',
            'givePt_gamatron' => '0',
            'limitGames[0]' => 'dg',
            'limitGames[1]' => 'sa',
            'limitGames[2]' => 'sexy',
            'limitGames[3]' => 'wm',
            'limitGames[4]' => 'bg',
            'limitGames_dg[]' => 'B',
            'commission_dg' => $this->comm,
            'limitGames_sa[]' => 'B',
            'commission_sa' => $this->comm,
            'limitGames_sexy[]' => 'B',
            'commission_sexy' => $this->comm,
            'limitGames_wm[]' => 'B',
            'commission_wm' => $this->comm,
            'limitGames_bg[]' => 'A',
            'commission_bg' => $this->comm
        ];

        $param = $this->setParam($setParam, 'addmember_p');

        $response = $this->push();
        $response = json_decode($response, true);

        if($debug){
            return compact('param', 'response');
        }

        return $response;
    }

    public function setBetLimit($data){

        $ref_id = $data['ref_id'];
        $response = [];
        foreach ($data['limits'] as $game => $limit){
            $setParam = [
                'id' => $ref_id,
                'game' => $game,
                'limit' => $limit,
            ];
            $response[] = $this->actionPost($setParam, 'editplayer/limitbet');
        }

        return $response;

    }

    public function actionPost($data, $url, $debug = false)
    {

        $param = $this->setParam($data, $url);

        $response = $this->push();
        $response = json_decode($response, true);

        if($debug) {
            return compact('param', 'response');
        }

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
