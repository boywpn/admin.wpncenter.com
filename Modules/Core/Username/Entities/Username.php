<?php

namespace Modules\Core\Username\Entities;

use GeneaLabs\LaravelModelCaching\CachedModel;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Boards\Entities\Boards;
use Modules\Core\Boards\Entities\BoardsUsers;
use Modules\Core\Games\Entities\Games;
use Modules\Member\Members\Entities\Members;
use Modules\Platform\Core\Traits\Commentable;
use Spatie\Activitylog\Traits\LogsActivity;

class Username extends Model
{
    use SoftDeletes, LogsActivity, Commentable, BelongsToTenants;

    const COLORS = [
        0 => 'bg-red',
        1 => 'bg-green'
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const FORM_REMOVE_CREATE = [
        'username'
    ];
    const FORM_REMOVE_EDIT = [
        // 'username'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    protected static $logAttributes = [
        'usernameBoard.name',
        'usernameMember.name',
        'member_at',
        'code',
        'username',
        'password',
        'bet_limit',
        'is_active',
        'is_created',
        'is_created_at',
        'member_at',
        'notes'
    ];

    public $table = 'core_username';

    public $fillable = [
        'betitem_at',
        'next_start',
        'to_time',
        'from_time',
    ];

    protected $dates = ['deleted_at', 'created_at', 'updated_at', 'member_at'];

    /**
     * Set for custom tab data
     */
    public function setTabData($id){

        $value = self::find($id);
        $board = Boards::findOrFail($value->board_id);

        $data = [
            'entity' => $value,
            'games' => Games::getGamesById($board->game_id, ['is_betlimit' => 1]),
        ];

        return $data;

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usernameBoard()
    {
        return $this->belongsTo(Boards::class,'board_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usernameMember()
    {
        return $this->belongsTo(Members::class, 'member_id');
    }

    public static function getMemberUsername($member_id){

        $data = Username::where('core_username.is_active', 1)
            ->whereNotNull('core_username.member_id')
            ->where('core_username.member_id', $member_id)
            ->join('core_boards', function ($join) {
                $join->on('core_username.board_id', '=', 'core_boards.id');
            })
            ->join('core_games', function ($join) {
                $join->on('core_boards.game_id', '=', 'core_games.id');
            })
            ->select(
                'core_username.*',
                'core_boards.name as board_name',
                'core_games.name as game_name'
            )
            ->get()->toArray();

        return $data;

    }

    public static function getUsernameByUser($user){

        $username = Username::where('core_username.username', $user)
            ->leftJoin('core_boards', 'core_username.board_id', '=', 'core_boards.id')
            ->leftJoin('core_games', 'core_boards.game_id', '=', 'core_games.id')
            ->leftJoin('member_members', 'core_username.member_id', '=', 'member_members.id')
            // ->where('core_boards.report_api', 1)
            // ->where('core_boards.is_active', 1)
            ->select(
                'core_username.id as username_id',
                'core_username.username',
                'core_username.next_start',
                'core_username.member_id as member_id',
                'core_boards.id as board_id',
                'core_boards.name as board_name',
                'core_boards.api_code as api_code',
                'core_games.id as game_id',
                'core_games.name as game_name',
                'core_games.code as game_code',
                'core_games.taking as game_taking',
                'member_members.agent_id as agent_id',
                'core_boards.agent_id as b_agent_id'
            )
            ->first();

        if(!$username){
            return [];
        }

        return $username->toArray();

    }

}
