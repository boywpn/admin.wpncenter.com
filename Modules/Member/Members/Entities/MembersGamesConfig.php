<?php

namespace Modules\Member\Members\Entities;

use App\Models\Banks;
use GeneaLabs\LaravelModelCaching\CachedModel;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Games\Entities\Games;
use Modules\Platform\Core\Traits\Commentable;
use Spatie\Activitylog\Traits\LogsActivity;

class MembersGamesConfig extends CachedModel
{

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $table = 'member_members_games_config';

    protected $dates = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gamesConfigMember()
    {
        return $this->belongsTo(Members::class, 'member_id');
    }

    public function varGamesConfig()
    {
        return $this->hasMany(MembersGamesConfigVar::class, 'conf_id');
    }

    public static function getMemberCommissions($member_id){

        $conf = self::where('member_id', $member_id)
            ->with(['varGamesConfig' => function($query){
                $query->select('*');
            }])
            ->orderBy('created_at', 'desc')
            ->first();

        if(!$conf){

            // if no have member set use default
            $games = Games::where('is_active', 1)
                ->where('is_commission', 1)
                ->with(['typesGame' => function($query){
                    $query->where('is_commission', 1)->select('*');
                }])
                ->get();

            if(!$games){
                return [];
            }
            $return = [];
            foreach ($games->toArray() as $game){

                $types = [];
                foreach ($game['types_game'] as $type){
                    $types[$type['id']] = $type['start_comm'];
                }

                $return[$game['id']] = $types;

            }

            return $return;
        }

        $conf = $conf->toArray();
        $return = [];
        foreach ($conf['var_games_config'] as $value){
            $return[$value['game_id']] = json_decode($value['commissions'], true);
        }

        return $return;

    }

}
