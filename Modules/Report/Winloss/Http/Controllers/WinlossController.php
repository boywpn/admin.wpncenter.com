<?php

namespace Modules\Report\Winloss\Http\Controllers;

use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Modules\Core\Games\Entities\GamesTypes;
use Modules\Core\Username\Entities\Username;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Report\Winloss\Entities\ViewWinlossAgent;
use Modules\Report\Winloss\Entities\Winloss;
use Modules\Report\Winloss\Entities\WinlossTmpAgent;

class WinlossController extends ModuleCrudController
{

    protected $validateModule = false;

    protected $languageFile = 'report/winloss::winloss';

    protected $winlossView = 'report/winloss::games.index';

    protected $winlossGameRoute = 'report.winloss.game';
    protected $winlossGameRouteSearch = 'report.winloss.game.search';

    protected $winlossClass = Winloss::class;

    public $subFixTitle;

    public function winlossNew($game = null){

        $this->winlossView = 'report/winloss::games.index-new';
        $this->winlossGameRoute = 'report.winloss-new.game';
        $this->winlossGameRouteSearch = 'report.winloss-new.game.search';

        $this->winlossClass = WinlossTmpAgent::class;

        return $this->winloss($game);

    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function winloss($game = null)
    {

        $request = \App::make(Request::class);
        $input = $request->all();
        $formData = $input;

//        if(!empty($formData) && !isset($formData['type'])){
//            flash(trans('report/winloss::winloss.please_check_type'))->error();
//            return redirect()->route('report.winloss.game', $game);
//        }

        if(empty($input)){
            $formData = [
//                'from' => genDateFilter('0 day'),
//                'to' => genDateFilter('1 day'),
//                'from_time' => '12:00:00',
//                'to_time' => '11:59:59',
                'type_all' => 'on',
                'type' => [],
                'role' => 'ss',
                'user' => ''
            ];
        }

        if(empty($game)) {

            $view = view('report/winloss::layouts.winloss');

            $view->with('gameRoute', $this->winlossGameRoute);
            $view->with('gameRouteSearch', $this->winlossGameRouteSearch);

            return $view;

        }else{

            if($game == "sa"){

                $game_id = 3; // have to change manual

                $formData['game'] = $game_id;
                $formData['filter'] = 'payout_time';
                if(empty($input)){
                    $formData['from'] = genDateFilter('0 day');
                    $formData['to'] = genDateFilter('0 day');
                    $formData['from_time'] = '12:00:00';
                    $formData['to_time'] = '23:59:59';
                }

                $data['gameCode'] = $game;
                $data['gameID'] = $game_id;
                $data['subFixTitle'] = "SA Gaming";
                $data['gameType'] = GamesTypes::getTypeFromGame($game_id);
                $data['dataWinloss'] = $this->getData($formData, $game_id);

            }

            elseif($game == "dg"){

                $game_id = 1; // have to change manual

                $formData['game'] = $game_id;
                $formData['filter'] = 'bet_time';
                if(empty($input)){
                    $formData['from'] = genDateFilter('0 day');
                    $formData['to'] = genDateFilter('0 day');
                    $formData['from_time'] = '00:00:00';
                    $formData['to_time'] = '23:59:59';
                }

                $data['gameCode'] = $game;
                $data['gameID'] = $game_id;
                $data['subFixTitle'] = "Dream Gaming";
                $data['gameType'] = GamesTypes::getTypeFromGame($game_id);
                $data['dataWinloss'] = $this->getData($formData, $game_id);

            }

            elseif($game == "aec"){

                $game_id = 19; // have to change manual

                $formData['game'] = $game_id;
                $formData['filter'] = 'work_time';
                if(empty($input)){
                    $formData['from'] = genDateFilter('0 day');
                    $formData['to'] = genDateFilter('0 day');
                    $formData['from_time'] = '00:00:00';
                    $formData['to_time'] = '23:59:59';
                }

                $data['gameCode'] = $game;
                $data['gameID'] = $game_id;
                $data['subFixTitle'] = "AEC.BET";
                $data['gameType'] = GamesTypes::getTypeFromGame($game_id);
                $data['dataWinloss'] = $this->getData($formData, $game_id);

                // return $data['dataWinloss'];

            }

            elseif($game == "sexy"){

                $game_id = 20; // have to change manual

                $formData['game'] = $game_id;
                $formData['filter'] = 'work_time';
                if(empty($input)){
                    $formData['from'] = genDateFilter('0 day');
                    $formData['to'] = genDateFilter('0 day');
                    $formData['from_time'] = '00:00:00';
                    $formData['to_time'] = '23:59:59';
                }

                $data['gameCode'] = $game;
                $data['gameID'] = $game_id;
                $data['subFixTitle'] = "Sexy Baccarat";
                $data['gameType'] = GamesTypes::getTypeFromGame($game_id);
                $data['dataWinloss'] = $this->getData($formData, $game_id);

            }

            /**
             * CasinoSH
            */
            elseif($game == "csh"){

                $game_id = 25; // have to change manual

                $formData['game'] = $game_id;
                $formData['filter'] = 'work_time';
                if(empty($input)){
                    $formData['from'] = genDateFilter('0 day');
                    $formData['to'] = genDateFilter('0 day');
                    $formData['from_time'] = '00:00:00';
                    $formData['to_time'] = '23:59:59';
                }

                $data['gameCode'] = $game;
                $data['gameID'] = $game_id;
                $data['subFixTitle'] = "CasinoSH";
                $data['gameType'] = GamesTypes::getTypeFromGame($game_id);
                $data['dataWinloss'] = $this->getData($formData, $game_id);

            }

            /**
             * IBC Sportsbook
            */
            elseif($game == "ibc"){

                $game_id = 21; // have to change manual

                $formData['game'] = $game_id;
                $formData['filter'] = 'work_time';
                if(empty($input)){
                    $formData['from'] = genDateFilter('0 day');
                    $formData['to'] = genDateFilter('0 day');
                    $formData['from_time'] = '00:00:00';
                    $formData['to_time'] = '23:59:59';
                }

                $data['gameCode'] = $game;
                $data['gameID'] = $game_id;
                $data['subFixTitle'] = "IBC SportsBook";
                $data['gameType'] = GamesTypes::getTypeFromGame($game_id);
                $data['dataWinloss'] = $this->getData($formData, $game_id);

            }

            /**
             * Sbobet API
            */
            elseif($game == "sboapi"){

                $game_id = 24; // have to change manual

                $formData['game'] = $game_id;
                $formData['filter'] = 'work_time';
                if(empty($input)){
                    $formData['from'] = genDateFilter('0 day');
                    $formData['to'] = genDateFilter('0 day');
                    $formData['from_time'] = '00:00:00';
                    $formData['to_time'] = '23:59:59';
                }

                $data['gameCode'] = $game;
                $data['gameID'] = $game_id;
                $data['subFixTitle'] = "SBOBET API";
                $data['gameType'] = GamesTypes::getTypeFromGame($game_id);
                $data['dataWinloss'] = $this->getData($formData, $game_id);

            }

            /**
             * TFGaming E-Sport
            */
            elseif($game == "tfg"){

                $game_id = 27; // have to change manual

                $formData['game'] = $game_id;
                $formData['filter'] = 'work_time';
                if(empty($input)){
                    $formData['from'] = genDateFilter('0 day');
                    $formData['to'] = genDateFilter('0 day');
                    $formData['from_time'] = '00:00:00';
                    $formData['to_time'] = '23:59:59';
                }

                $data['gameCode'] = $game;
                $data['gameID'] = $game_id;
                $data['subFixTitle'] = "TFGaming E-Sport";
                $data['gameType'] = GamesTypes::getTypeFromGame($game_id);
                $data['dataWinloss'] = $this->getData($formData, $game_id);

            }

            /**
             * LottoSH
             */
            elseif($game == "lottosh"){

                $game_id = 23; // have to change manual

                $formData['game'] = $game_id;
                $formData['filter'] = 'work_time';
                if(empty($input)){
                    $formData['from'] = genDateFilter('0 day');
                    $formData['to'] = genDateFilter('0 day');
                    $formData['from_time'] = '00:00:00';
                    $formData['to_time'] = '23:59:59';
                }

                $data['gameCode'] = $game;
                $data['gameID'] = $game_id;
                $data['subFixTitle'] = "LottoSH";
                $data['gameType'] = GamesTypes::getTypeFromGame($game_id);
                $data['dataWinloss'] = $this->getData($formData, $game_id);

            }

        }

//        return $formData;

        unset($formData['_token']);
        if(isset($request['role'])){
            $formData['role'] = $request['role'];
        }
        if(isset($formData['user'])){
            unset($formData['user']);
        }
        if(isset($formData['id'])){
            unset($formData['id']);
        }

        $data['request'] = $request->all();
        $data['queryString'] = $request->query();
        $data['formData'] = $formData;

        unset($formData['role']);
        unset($formData['user']);
        unset($formData['id']);

        $data['urlParam'] = http_build_query($formData);

//        print_r($data);

//        return view('report/winloss::games.'.$game, $data);

        $view = view($this->winlossView, $data);

        $view->with('gameRoute', $this->winlossGameRoute);
        $view->with('gameRouteSearch', $this->winlossGameRouteSearch);

        return $view;

        // return view($this->winlossView, $data);

    }

    public function getData($formData, $game_id){

        $resp = App::make($this->winlossClass);

        if($formData['role'] == "mm"){
            //$resp = App::make(Winloss::class);
            $res = $resp::getWinlossList($formData, $game_id);
        }else {
            //$resp = App::make($this->winlossClass);
            $res = $resp::getWinloss($formData, $game_id);
        }

        return $res;

    }

}
