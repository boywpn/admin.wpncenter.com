<?php

namespace Modules\Member\Members\Entities;

use GeneaLabs\LaravelModelCaching\CachedModel;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Agents\Entities\Agents;
use Modules\Core\Boards\Entities\Boards;
use Modules\Core\Games\Entities\Games;
use Modules\Core\Username\Entities\Username;
use Modules\Core\Username\Entities\ViewCheckUsername;
use Modules\Platform\Core\Traits\Commentable;
use Modules\Platform\Core\Traits\FunctionalTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class Members extends Model
{
    use SoftDeletes, LogsActivity, Commentable, BelongsToTenants, FunctionalTrait;

    const COLORS = [
        0 => 'bg-red',
        1 => 'bg-green'
    ];

    /**
     * For Remove Form
    */
    const FORM_REMOVE_CREATE = [

    ];
    const FORM_REMOVE_EDIT = [
        // 'password'
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
        'username',
        'password',
        'phone',
        'email',
        'facebook',
        'lineid',
        'howtoknow',
        'notes',
        'membersAgent.name',
        'membersStatus.name',
        'is_active',
    ];

    public $table = 'member_members';

    public $fillable = [
        'name',
        'username',
        'password',
        'password_key',
        'phone',
        'email',
        'facebook',
        'lineid',
        'howtoknow',
        'notes',
        'agent_id',
        'status_id',
        'first_update',
        'first_update_at',
        'is_active',
        'company_id',
    ];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function membersAgent()
    {
        return $this->belongsTo(Agents::class, 'agent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function membersStatus()
    {
        return $this->belongsTo(MembersStatus::class, 'status_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usernameMember()
    {
        return $this->hasMany(Username::class, 'member_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function banksMember()
    {
//        return $this->hasMany(MembersBanks::class, 'member_id');
        return $this->hasOne(MembersBanks::class, 'member_id');
    }

    /**
     * Set for custom tab data
     */
    public function setTabData($id){

        $value = self::find($id);

        $data = [
            'entity' => $value,
            'games' => Games::getGameComm(),
            'commissions' => MembersGamesConfig::getMemberCommissions($id)
        ];

        return $data;

    }

    public function checkExistCode($partner_id, $member_id){

        $username = MembersUsername::where('partner_id', $partner_id)
            ->where('member_id', $member_id)
            ->first();

        if(!$username){
            return "";
        }

        $arrData = array(
            'exist' => true,
            'partner_id' => $username->partner_id,
            'board_number' => $username->board_number,
            'username_code' => $username->username_code
        );

        return $arrData;

    }

    /**
     * Created Username from Agent
     */
    public static function getUsernameCode($agent_id, $member_id, $filter = null){

        $agent = Agents::find($agent_id);
        $partner_id = $agent->partner_id;

        // Check have exist code
        $member = new Members();
        $exist = $member->checkExistCode($partner_id, $member_id);
        if(!empty($exist)){
            return $exist;
        }

        // check from view check_username
        $username = ViewCheckUsername::where('check_username.partner_id', $partner_id)
            ->join('core_boards', 'core_boards.id', '=', 'check_username.board_id')
            ->whereNull('check_username.member_id')
            ->where('check_username.code', '!=', '000')
            ->where('core_boards.for_test', 0)
            ->where('core_boards.is_active', 1)
            ->orderBy('check_username.board_number', 'asc')
            ->orderBy('check_username.code', 'asc')
            ->select(
                'check_username.*'
            )
            ->first();

        $username = $username->toArray();

        $arrData = array(
            'exist' => false,
            'username_id' => null,
            'username' => null,
            'partner_id' => $partner_id,
            'board_number' => $username['board_number'],
            'username_code' => $username['code']
        );

        return $arrData;

        /**
         * Old Function Not use
        */
        $member = Members::find($member_id);

        // Select Board Order By board_number
        $boards = Boards::where('core_boards.is_active', 1)
            ->join('core_games', 'core_games.id', '=', 'core_boards.game_id')
            ->where('core_boards.partner_id', $partner_id)
            ->when(isset($filter['board_id']), function($query) use ($filter){
                $query->where('id', $filter['board_id']);
            })
            ->when(isset($filter['game_id']), function($query) use ($filter){
                $query->where('game_id', $filter['game_id']);
            })
            ->when($member, function($query) use ($member){ // For Test Member
                if($member->for_test) { // If is test have to use test boards
                    $query->where('for_test', 1);
                }else{
                    $query->where('for_test', 0);
                }
            })
            ->where('core_games.is_active', 1)
            ->select(
                'core_boards.*'
            )
            ->orderBy('core_boards.board_number', 'asc')
            ->get();

//        return $boards->toArray();
//        exit;

        $boards = $boards->groupBy('board_number');
        $boards = $boards->toArray();

//        return $boards;

        // Loop check username from boards
        $arrData = array();
        foreach ($boards as $board_number => $board){

            $board_id = $board[0]['id'];

            $usernames = ViewCheckUsername::where('is_active', 1)
                ->whereNull('member_id')
                ->where('code', '!=', '000')
                ->where('board_id', $board_id)
                ->orderBy('id', 'asc')
                ->limit(1)
                ->get()
                ->toArray();


            foreach ($usernames as $username) {
                $arrData[] = array(
                    'exist' => false,
                    'username_id' => $username['id'],
                    'username' => null,
                    'partner_id' => $partner_id,
                    'board_number' => $board_number,
                    'username_code' => $username['code']
                );
            }

            $arrTest[$board_id][] = $usernames;

        }

        $return = (isset($arrData[0])) ? $arrData[0] : "";

        return $arrTest;

    }

    public static function getUsernameCodeNew($agent_id, $member_id, $filter = null){

        $agent = Agents::find($agent_id);
        $partner_id = $agent->partner_id;

        // Check have exist code
        $member = new Members();
        $exist = $member->checkExistCode($partner_id, $member_id);
        if(!empty($exist)){
            // return $exist;
        }

        // Check last number on boards
        $user_log = MembersUsername::where('partner_id', $partner_id)
            ->orderBy('created_at', 'DESC')
            ->first();

        // Check board have enough
        $board_have = Boards::where('partner_id', $partner_id)
            ->where('board_number', $user_log->board_number)
            ->first();

        // Check user on board
        $new_code = $user_log->username_code + 1;
        $new_code = 501;
        $user_have = Username::where('is_active', 1)
            ->whereNull('member_id')
            ->where('code', '!=', '000')
            ->where('board_id', $board_have->id)
            ->orderBy('id', 'asc')
            ->first();

        // Check new board
        if(!$user_have){
            $board_have = Boards::where('partner_id', $partner_id)
                ->where('id', '>', $board_have->id)
                ->first();

            $user_have = Username::where('is_active', 1)
                ->whereNull('member_id')
                ->where('code', '!=', '000')
                ->where('board_id', $board_have->id)
                ->orderBy('id', 'asc')
                ->first();
        }

        $arrData = array(
            'exist' => false,
            'username_id' => $user_have->id,
            'username' => $user_have->username,
            'partner_id' => $partner_id,
            'board_number' => $user_log->board_number,
            'username_code' => $new_code
        );

        return $arrData;

        return compact('user_log', 'board_log', 'user_have', 'arrData');

        $member = Members::find($member_id);

        // Select Board Order By board_number
        $boards = Boards::where('is_active', 1)
            ->where('partner_id', $partner_id)
            ->when(isset($filter['board_id']), function($query) use ($filter){
                $query->where('id', $filter['board_id']);
            })
            ->when(isset($filter['game_id']), function($query) use ($filter){
                $query->where('game_id', $filter['game_id']);
            })
            ->when($member, function($query) use ($member){ // For Test Member
                if($member->for_test) { // If is test have to use test boards
                    $query->where('for_test', 1);
                }else{
                    $query->where('for_test', 0);
                }
            })
            ->orderBy('board_number', 'asc')
            ->get();

//        return $boards->toArray();
//        exit;

        $boards = $boards->groupBy('board_number');
        $boards = $boards->toArray();

        // Loop check username from boards
        $arrData = array();
        foreach ($boards as $board_number => $board){

            $board_id = $board[0]['id'];

            $usernames = Username::where('is_active', 1)
                ->whereNull('member_id')
                ->where('code', '!=', '000')
                ->where('board_id', $board_id)
                ->orderBy('id', 'asc')
                ->limit(1)
                ->get()
                ->toArray();

            foreach ($usernames as $username) {
                $arrData[] = array(
                    'exist' => false,
                    'username_id' => $username['id'],
                    'username' => $username['username'],
                    'partner_id' => $partner_id,
                    'board_number' => $board_number,
                    'username_code' => $username['code']
                );
            }

        }

        $return = (isset($arrData[0])) ? $arrData[0] : "";

        return $return;

    }

    public static function getMember($member_id){

        $data = Members::where('member_members.id', $member_id)
            ->with(['banksMember' => function($query){
                $query->select('id','member_id','bank_id','bank_account','bank_number','is_main','is_active');
            }, 'banksMember.banksBank' => function($query){
                $query->select('id','code','name');
            }])
            ->with(['membersAgent' => function($query){
                $query->select('id','partner_id','name');
            }, 'membersAgent.agentsPartner' => function($query){
                $query->select('id','name','website');
            }, 'membersAgent.agentsPartner.partnersPromotion' => function($query){
                $query->where('core_promotions.is_active', 1)->select('id','partner_id','name','title','is_front')->orderBy('core_promotions.id', 'asc');
            }])
            ->with(['usernameMember' => function($query){
                $query->select('id','member_id','board_id','username','is_active');
            }, 'usernameMember.usernameBoard' => function($query){
                $query->select('id','partner_id','game_id','name');
            }, 'usernameMember.usernameBoard.boardsGame' => function($query){
                $query->select('id','name','code');
            }])
            ->with(['membersStatus' => function($query){
                $query->select('id','name');
            }])
            ->select('id','agent_id','name','phone','email','facebook','lineid','notes','is_active','status_id','created_from')
            ->first()->toArray();

        return $data;

    }

}
