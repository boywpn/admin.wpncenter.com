<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Games\Dg\DGController;
use App\Http\Controllers\Games\Og\OGController;
use App\Http\Controllers\Games\Sa\SAController;
use Illuminate\Support\Facades\Artisan;
use Modules\Platform\Core\Repositories\GenericRepository;

class AppController extends Controller
{

    public function __construct()
    {
        // Artisan::call('cache:clear');
    }

    /**
     * Module Repository
     * @var
     */
    protected $repository = GenericRepository::class;

    /**
     * Module Entity Class
     * @var
     */
    protected $entityClass;

    /**
     * Create instance of module repository or use generic repository
     *
     * @return mixed
     */
    protected function getRepository()
    {
        if ($this->repository == GenericRepository::class) {
            $repository = \App::make($this->repository);
            $repository->setupModel($this->entityClass);
        } else {
            $repository = \App::make($this->repository);
        }

        return $repository;
    }

    public function error($msg)
    {
        $json = array(
            'status' => false,
            'data' => array(),
            'message' => $msg,
        );
        $output =  json_encode($json,JSON_UNESCAPED_UNICODE);
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        return $output;
    }

    public function success($msg, $data)
    {
        $json = array(
            'status' => true,
            'data' => $data,
            'message' => $msg,
        );
        $output =  json_encode($json,JSON_UNESCAPED_UNICODE);
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        return $output;
    }

    /**
     * Create instance of default game configs
     *
     */
    protected function setDefaultGamesConfig(){

        $set = [
            'sa' => SboController::BET_LIMIT,
            'dg' => DGController::BET_LIMIT,
            'og' => OGController::BET_LIMIT,
        ];

        return $set;

    }

    public function saveFixture($betlist_id, $game_id, $data){

        if(empty($data)){
            return null;
        }

        $json = json_decode($data, true);
        $subBet = $json['subBet'][0];

        if(!isset($json['subBet'][0])){
            return null;
        }

        $hash = md5($subBet['league'].$subBet['match'].$subBet['winlostDate']);

        $xmatch = explode(' vs ', $subBet['match']);

        $arrFixture = [
            'game_id' => $game_id,
            'league' => $subBet['league'],
            'match' => $subBet['match'],
            'home' => trim($xmatch[0]),
            'away' => trim($xmatch[1]),
            'match_date' => $subBet['winlostDate'],
            'winlost_date' => $subBet['winlostDate'],
            'hash' => $hash
        ];

        $arrItems = [
            'fixture_id' => null,
            'betlist_id' => $betlist_id,
            'bet_option' => $subBet['betOption'],
            'market_type' => $subBet['marketType'],
            'live_score' => $subBet['liveScore'],
            'ht_score' => $subBet['htScore'],
            'ft_score' => $subBet['ftScore'],
            'match_time' => $json['winLostDate'],
            'winlost_time' => $json['winLostDate'],
            'bet_time' => $json['orderTime'],
            'bet_amount' => $json['stake'],
            'odds_style' => $json['oddsStyle'],
            'is_haft' => $json['isHalfWonLose'],
            'is_live' => $json['isLive'],
            'is_home' => null,
        ];

        return compact('arrFixture', 'arrItems');

    }

}
