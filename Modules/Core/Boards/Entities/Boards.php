<?php

namespace Modules\Core\Boards\Entities;

use GeneaLabs\LaravelModelCaching\CachedModel;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Agents\Entities\Agents;
use Modules\Core\Games\Entities\Games;
use Modules\Core\Partners\Entities\Partners;
use Modules\Core\Username\Entities\Username;
use Modules\Platform\Core\Traits\Commentable;
use Modules\Report\Winloss\Entities\Winloss;
use Spatie\Activitylog\Traits\LogsActivity;

class Boards extends Model
{
    use SoftDeletes, LogsActivity, Commentable, BelongsToTenants;

    const COLORS = [
        0 => 'bg-red',
        1 => 'bg-green'
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    protected static $logAttributes = [
        'name',
        'user_prefix',
        'board_number',
        'notes',
//        'boardsAgent.name',
        'boardsPartner.name',
        'boardsGame.name',
        'is_active',
        'for_test',
        'use_api',
        'api_code',
        'report_api',
    ];

    public $table = 'core_boards';

    public $fillable = [
        'name',
        'user_prefix',
        'board_number',
        'notes',
        'partner_id',
        'game_id',
        'company_id',
        'is_active',
        'for_test',
        'use_api',
        'report_api',
        'api_code'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
//        'api_code'
    ];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    public static function getSelectOption(){
        $main = Boards::all();

        $data = [];
        foreach ($main as $main){
            $data[] = [
                'value' => $main->id,
                'label' => $main->name
            ];
        }

        return $data;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function boardsGame()
    {
        return $this->belongsTo(Games::class,'game_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function boardsAgent()
    {
        return $this->belongsTo(Agents::class, 'agent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function boardsPartner()
    {
        return $this->belongsTo(Partners::class, 'partner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usersBoard()
    {
        return $this->hasMany(BoardsUsers::class, 'board_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function membersBoard()
    {
        return $this->hasMany(Username::class, 'board_id');
    }

    public static function getBoardByGame($game){

        $boards = self::where('core_boards.report_api', 1)
            ->where('core_boards.is_active', 1)
            ->where('core_boards.for_test', 0)
            ->leftJoin('core_games', 'core_boards.game_id', '=', 'core_games.id')
            ->where('core_games.code', $game)
            ->select(
                'core_boards.id as board_id',
                'core_boards.name as board_name',
                'core_boards.api_code as api_code',
                'core_games.id as game_id',
                'core_games.name as game_name',
                'core_games.code as game_code',
                'core_games.taking as game_taking'
            )
            ->get();

        return $boards->toArray();

    }

    public static function getBoardByGameGroup($game, $bd_id = null){

        $boards = self::where('core_boards.report_api', 1)
            ->where('core_boards.is_active', 1)
            // ->where('core_boards.for_test', 0)
            ->when(!empty($bd_id), function($query) use ($bd_id){
                $query->where('core_boards.id', $bd_id);
            })
            ->leftJoin('core_games', 'core_boards.game_id', '=', 'core_games.id')
            ->where('core_games.code', $game)
            ->groupBy('core_boards.ref')
            ->select(
                'core_boards.id as board_id',
                'core_boards.ref as ref',
                'core_boards.lastkey as lastkey',
                'core_boards.name as board_name',
                'core_boards.api_code as api_code',
                'core_games.id as game_id',
                'core_games.name as game_name',
                'core_games.code as game_code',
                'core_games.taking as game_taking'
            )
            ->get();

        return $boards->toArray();

    }

    public static function getBoardByID($id){

        $boards = self::where('core_boards.id',$id)
            ->where('core_boards.is_active', 1)
            ->leftJoin('core_games', 'core_boards.game_id', '=', 'core_games.id')
            ->groupBy('core_boards.ref')
            ->select(
                'core_boards.id as board_id',
                'core_boards.name as board_name',
                'core_boards.api_code as api_code',
                'core_games.id as game_id',
                'core_games.name as game_name',
                'core_games.code as game_code',
                'core_games.taking as game_taking'
            )
            ->first();

        return $boards->toArray();

    }

    public static function getBoardMemberByGame($formData, $game){

        $game = Games::where('code', $game)->first();

//        $boards = self::where('is_active', 1)
//            ->where('for_test', 0)
//            ->where('game_id', $game->id)
//            ->with(['membersBoard' => function($query){
//                $query->whereNotNull('member_id')->select('id','board_id','member_id','username');
//            }])
//            ->select('id','name','member_prefix')
//            ->get();
//
//        return $boards->toArray();

        $formData['game'] = $game->id;
        $formData['filter'] = 'work_time';

        $bets = Winloss::getWinlossMember($formData, $game->id);

        return $bets;

    }

}
