<?php

namespace Modules\Core\Agents\Entities;

use GeneaLabs\LaravelModelCaching\CachedModel;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Games\Entities\Games;
use Modules\Core\Partners\Entities\Partners;
use Modules\Member\Members\Entities\Members;
use Modules\Platform\Core\Traits\Commentable;
use Modules\Platform\Core\Traits\FunctionalTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class Agents extends Model
{
    use SoftDeletes, LogsActivity, Commentable, BelongsToTenants, FunctionalTrait;

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
        'email',
        'phone',
        'notes',
        'agentsParent.name',
        'agentsPartner.name',
        'agentsStatus.name',
        'is_active',
    ];

    public $table = 'core_agents';

    public $fillable = [

    ];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agentsPartner()
    {
        return $this->belongsTo(Partners::class, 'partner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agentsParent()
    {
        return $this->belongsTo(Agents::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agentsStatus()
    {
        return $this->belongsTo(AgentsStatus::class, 'status_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members()
    {
        return $this->HasMany(Members::class, 'agent_id');
    }

    /**
     * Set for custom tab data
     */
    public function setTabData($id){

        $value = self::find($id);

        $data = [
            'entity' => $value,
            'games' => Games::getGameComm(),
            'shares' => AgentsShare::getAgentShare($id),
        ];

        return $data;

    }

    public static function getAgentBoardSelect(){

        return Agents::all()->pluck('name', 'id')->toArray();

    }

//    public static function getAgentSharing($agent_id, $game_id){
//
//        $agent = AgentsShare::where('agent_id', $agent_id)
//            ->
//            ->orderBy('id', 'desc')
//            ->first();
//
//        if(!$agent){
//            return [];
//        }
//
//        $agent = $agent->toArray();
//
//        return $agent;
//
//    }
}
