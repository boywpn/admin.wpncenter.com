<?php

namespace Modules\Report\Winloss\Entities;

use GeneaLabs\LaravelModelCaching\CachedModel;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Core\Agents\Entities\Agents;
use Modules\Core\Boards\Entities\Boards;
use Modules\Core\Games\Entities\Games;
use Modules\Core\Games\Entities\GamesTypes;
use Modules\Core\Username\Entities\Username;
use Modules\Member\Members\Entities\Members;
use Modules\Platform\Core\Traits\Commentable;
use Modules\Platform\Core\Traits\FunctionalTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class WinlossTmpAgent extends CachedModel
{

    public static function getWinloss($formData, $game_id){

        if($formData['role'] == "ss"){

            return self::getWinlossPartner($formData, $game_id);

        }
        elseif($formData['role'] == "ag"){

            return self::getWinlossAgent($formData, $game_id);

        }

    }

    public static function getWinlossPartner($formData, $game_id){

        $from = $formData['from']." ".$formData['from_time'];
        $to = $formData['to']." ".$formData['to_time'];

        $query = DB::table('report_betlists_temp_agent AS ta')
            ->leftJoin('core_partners AS pn', 'pn.id', '=', 'ta.pn_id')
            ->where('ta.game_id', $game_id)
            ->where('pn.api_show_report', 1)
            ->select(
                'ta.agent_id',
                'ta.game_id',
                'ta.type_id',
                'ta.contact AS name',
                'ta.username AS ref',
                'ta.agent_id',
                'ta.pn_id AS partner_id',

                DB::raw('SUM(ta.valid_amount) AS valid_amount'),
                DB::raw('SUM(ta.turnover) AS turnover'),
                DB::raw('SUM(ta.stake) AS stake_count'),

                DB::raw('SUM(ta.member_winloss) AS member_winloss'),
                DB::raw('SUM(ta.member_comm) AS member_comm'),
                DB::raw('SUM(ta.member_total) AS member_total'),

                DB::raw('SUM(ta.agent_winloss) AS agent_winloss'),
                DB::raw('SUM(ta.agent_amount) AS agent_amount'),
                DB::raw('SUM(ta.agent_comm) AS agent_comm'),
                DB::raw('SUM(ta.agent_total) AS agent_total'),

                DB::raw('SUM(ta.super_winloss) AS super_winloss'),
                DB::raw('SUM(ta.super_amount) AS super_amount'),
                DB::raw('SUM(ta.super_comm) AS super_comm'),
                DB::raw('SUM(ta.super_total) AS super_total'),

                DB::raw('SUM(ta.company_winloss) AS company_winloss'),
                DB::raw('SUM(ta.company_amount) AS company_amount'),
                DB::raw('SUM(ta.company_comm) AS company_comm'),
                DB::raw('SUM(ta.company_total) AS company_total')
            )
            ->whereBetween('ta.work_date', [$from, $to])

            // For not all type
            ->when(!isset($formData['type_all']), function($query) use ($formData){
                if(isset($formData['type'])) {
                    return $query->whereIn('ta.type_id', $formData['type']);
                }
            })
            // ->whereNotIn('ta.pn_id', [6,7])
            ->groupBy('ta.agent_id')
            ->orderBy('ta.pn_id', 'asc')
            ->orderBy('ta.contact', 'asc')
            ->get();

        return $query->toArray();

    }

    public static function getWinlossAgent($formData, $game_id){

        $from = $formData['from']." ".$formData['from_time'];
        $to = $formData['to']." ".$formData['to_time'];

        $query = DB::table('report_betlists_temp_member AS tm')
            ->where('tm.game_id', $game_id)
            ->select(
                'tm.agent_id',
                'tm.game_id',
                'tm.type_id',
                'tm.contact AS name',
                'tm.username AS ref',
                'tm.agent_id',
                'tm.pn_id AS partner_id',

                DB::raw('SUM(tm.valid_amount) AS valid_amount'),
                DB::raw('SUM(tm.turnover) AS turnover'),
                DB::raw('SUM(tm.stake) AS stake_count'),

                DB::raw('SUM(tm.member_winloss) AS member_winloss'),
                DB::raw('SUM(tm.member_comm) AS member_comm'),
                DB::raw('SUM(tm.member_total) AS member_total'),

                DB::raw('SUM(tm.agent_winloss) AS agent_winloss'),
                DB::raw('SUM(tm.agent_amount) AS agent_amount'),
                DB::raw('SUM(tm.agent_comm) AS agent_comm'),
                DB::raw('SUM(tm.agent_total) AS agent_total'),

                DB::raw('SUM(tm.super_winloss) AS super_winloss'),
                DB::raw('SUM(tm.super_amount) AS super_amount'),
                DB::raw('SUM(tm.super_comm) AS super_comm'),
                DB::raw('SUM(tm.super_total) AS super_total'),

                DB::raw('SUM(tm.company_winloss) AS company_winloss'),
                DB::raw('SUM(tm.company_amount) AS company_amount'),
                DB::raw('SUM(tm.company_comm) AS company_comm'),
                DB::raw('SUM(tm.company_total) AS company_total')
            )
            ->whereBetween('tm.work_date', [$from, $to])

            // For not all type
            ->when(!isset($formData['type_all']), function($query) use ($formData){
                if(isset($formData['type'])) {
                    return $query->whereIn('tm.type_id', $formData['type']);
                }
            })
            ->where('tm.agent_id', $formData['id'])
            ->groupBy('tm.username')
            ->orderBy('tm.username', 'asc')
            ->get();

        return $query->toArray();

    }


    public static function getWinlossMember($formData, $game_id){

        $from = $formData['from']." ".$formData['from_time'];
        $to = $formData['to']." ".$formData['to_time'];

        $query = DB::table('report_betlists AS rb')
            ->where('rb.board_game_id', $game_id)
            ->select(
                'rb.agent_id',
                'rb.state',
                'ca.name',
                'ca.ref',
                'ca.id AS agent_id',
                'ca.partner_id AS partner_id',
                'cb.member_prefix',
                'rb.member_id',
                'mm.name AS member_name',
                'cu.username',
                'cu.id AS username_id',
                DB::raw('DATE_FORMAT(rb.bet_time, \'%Y-%m-%d\') AS bet_date'),
                DB::raw('DATE_FORMAT(rb.payout_time, \'%Y-%m-%d\') AS pay_date'),
                DB::raw('DATE_FORMAT(rb.work_time, \'%Y-%m-%d\') AS work_date'),

                DB::raw('SUM(rb.rolling) AS valid_amount'),
                DB::raw('SUM(IF(rb.result_amount > 0, rb.result_amount, (rb.result_amount*-1))) AS turnover'),
                DB::raw('COUNT(*) AS stack_count'),

                DB::raw('SUM(rb.result_amount) AS member_winloss'),
                DB::raw('SUM(rb.commission_amount) AS member_comm'),
                DB::raw('SUM(rb.result_amount + rb.commission_amount) AS member_total'),

                DB::raw('SUM(rb.agent_winloss) AS agent_winloss'),
                DB::raw('SUM(rb.agent_amount) AS agent_amount'),
                DB::raw('SUM(rb.agent_commission) AS agent_comm'),
                DB::raw('SUM(rb.agent_winloss + rb.agent_commission) AS agent_total'),

                DB::raw('SUM(rb.super_winloss) AS super_winloss'),
                DB::raw('SUM(rb.super_amount) AS super_amount'),
                DB::raw('SUM(rb.super_commission) AS super_comm'),
                DB::raw('SUM(rb.super_winloss + rb.super_commission) AS super_total'),

                DB::raw('SUM(rb.company_winloss) AS company_winloss'),
                DB::raw('SUM(rb.company_amount) AS company_amount'),
                DB::raw('SUM(rb.company_commission) AS company_comm'),
                DB::raw('SUM(rb.company_winloss + rb.company_commission) AS company_total')
            )
            ->leftJoin('core_agents AS ca', 'ca.id', '=', 'rb.agent_id')
            ->leftJoin('member_members AS mm', 'mm.id', '=', 'rb.member_id')
            ->leftJoin('core_username AS cu', 'cu.id', '=', 'rb.username_id')
            ->leftJoin('core_games AS cg', 'cg.id', '=', 'rb.board_game_id')
            ->leftJoin('core_games_types AS cgt', 'cgt.id', '=', 'rb.game_type_id')
            ->leftJoin('core_boards AS cb', 'cb.id', '=', 'cu.board_id')
            ->whereBetween('rb.'.$formData['filter'], [$from, $to])
            ->groupBy('rb.member_id')
            ->orderBy('cu.username', 'asc')
            //->where('rb.member_id', $member_id)->orderBy('rb.payout_time', 'desc')
            ->get();

        return $query->toArray();

    }
}
