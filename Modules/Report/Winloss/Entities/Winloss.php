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

class Winloss extends CachedModel
{

    public static function getWinloss($formData, $game_id){

        $from = $formData['from']." ".$formData['from_time'];
        $to = $formData['to']." ".$formData['to_time'];

        $query = DB::table('report_betlists AS rb')
            ->where('rb.board_game_id', $game_id)
            ->when($formData, function($query) use ($formData){
                if($formData['role'] != "mm"){
                    return $query->select(
                        'rb.agent_id',
                        'rb.board_game_id',
                        'rb.game_type_id',
                        'rb.member_id',
                        'ca.name',
                        'ca.ref',
                        'ca.id AS agent_id',
                        'cb.member_prefix',
                        'ca.partner_id AS partner_id',
                        'mm.name AS member_name',
                        'cu.username',
                        'cu.id AS username_id',
                        'cgt.name AS type_name',
//                        DB::raw('DATE_FORMAT(rb.bet_time, \'%Y-%m-%d\') AS bet_date'),
//                        DB::raw('DATE_FORMAT(rb.payout_time, \'%Y-%m-%d\') AS pay_date'),

                        DB::raw('SUM(rb.rolling) AS valid_amount'),
//                        DB::raw('SUM(IF(rb.result_amount > 0, rb.result_amount, (rb.result_amount*-1))) AS turnover'),
                        DB::raw('SUM(turnover) AS turnover'),
                        DB::raw('COUNT(rb.id) AS stack_count'),

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
                    );
                }
                else{
                    return $query->select(
                        'rb.*',
                        'ca.name',
                        'ca.ref',
                        'ca.id AS agent_id',
                        'cb.member_prefix',
                        'ca.partner_id AS partner_id',
                        'mm.name AS member_name',
                        'cu.username',
                        'cu.id AS username_id',
                        'cg.name AS game_name',
                        'cgt.name AS type_name',
                        'rb.bet_time AS bet_date',
                        'rb.payout_time AS pay_date',
                        'rb.work_time AS work_date'
//                        DB::raw('DATE_FORMAT(rb.bet_time, \'%d/%m/%y %r\') AS bet_date'),
//                        DB::raw('DATE_FORMAT(rb.payout_time, \'%d/%m/%y %r\') AS pay_date')
                    );
                }
            })
            ->leftJoin('core_agents AS ca', 'ca.id', '=', 'rb.agent_id')
            ->leftJoin('member_members AS mm', 'mm.id', '=', 'rb.member_id')
            ->leftJoin('core_username AS cu', 'cu.id', '=', 'rb.username_id')
            ->leftJoin('core_games AS cg', 'cg.id', '=', 'rb.board_game_id')
            ->leftJoin('core_games_types AS cgt', 'cgt.id', '=', 'rb.game_type_id')
            ->leftJoin('core_boards AS cb', 'cb.id', '=', 'cu.board_id')
            ->whereBetween('rb.'.$formData['filter'], [$from, $to])

            // For not all type
            ->when(!isset($formData['type_all']), function($query) use ($formData){
                if(isset($formData['type'])) {
                    return $query->whereIn('rb.game_type_id', $formData['type']);
                }
            })

            // For group by
            ->when($formData, function($query) use ($formData){
                if(empty($formData['user'])) {
                    return $query->groupBy('rb.agent_id')->orderBy('ca.partner_id', 'asc')->orderBy('ca.ref', 'asc');
                }else{
                    if($formData['role'] == "ag"){
                        // $agent = Agents::where('ref', $formData['user'])->select('id')->first();
                        return $query->where('rb.agent_id', $formData['id'])->groupBy('rb.member_id')->orderBy('cu.username', 'asc');
                    }elseif($formData['role'] == "mm"){
                        $username = Username::where('username', $formData['user'])->select('member_id')->first();
                        return $query->where('rb.member_id', $username->member_id)->orderBy('rb.bet_time', 'desc');
//                        return $query->where('rb.username_id', $formData['id'])->orderBy('rb.payout_time', 'desc');
                    }
                }
            })
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
