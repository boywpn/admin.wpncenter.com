<?php

namespace Modules\Report\TotalBets\Http\Controllers;

use App\Http\Controllers\Games\Trnf\Games\Ufa\UFAController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class TotalBetsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('report/totalbets::index');
    }

    public function ufa()
    {

        $agents = [
            'ufs1do' => [
                'username' => 'ufs1do',
                'password' => 'Zxcv0987@',
                'role' => 'ss',
                'rootUser' => 'ufs1'
            ],
            'ufs1caprite' => [
                'username' => 'ufs1caprite',
                'password' => 'Zxcv0987@',
                'role' => 'pa',
                'rootUser' => 'ufs1ca'
            ],
            'ufs1h5' => [
                'username' => 'ufs1h5',
                'password' => 'Zxcv0987@',
                'role' => 'pa',
                'rootUser' => 'ufs1h'
            ],
            'ufs11h11' => [
                'username' => 'ufs11h11',
                'password' => 'Zxcv0987@',
                'role' => 'pa',
                'rootUser' => 'ufs11h'
            ],
        ];

        $api = new UFAController();

        $totalBetsTotal = [];
        foreach ($agents as $u => $ag){

            $api->setEnv($ag);
            $login = $api->login();
            $totalBets = $api->getTotalBets();
            // $totalBets = $api->getTotalBetsCalculate();

            foreach ($totalBets as $key => $bet){

                $totalBetsTotal[$key]['league'] = $bet['league'];
                foreach ($bet['event'] as $k => $ev) {

                    $time = $ev['time'];
                    $event = $ev['event'];
                    $skey = md5($time.$event);

                    $totalBetsTotal[$key]['event'][$skey][$u] = $ev;
                }

            }

        }

        $view = view('report/totalbets::bets');
        $view->with('page_title', 'Total Bets');
        $view->with('bets', $totalBetsTotal);

        return $view;

    }

}
