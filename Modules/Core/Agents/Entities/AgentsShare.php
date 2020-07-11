<?php

namespace Modules\Core\Agents\Entities;

use GeneaLabs\LaravelModelCaching\CachedModel;
use Modules\Core\Games\Entities\Games;

class AgentsShare extends CachedModel
{

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $table = 'core_agents_share';

    protected $dates = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shareConfigAgent()
    {
        return $this->belongsTo(Agents::class, 'agent_id');
    }

    public function varShareConfig()
    {
        return $this->hasMany(AgentsShareVar::class, 'share_id');
    }

    public static function getAgentShare($id){

        $conf = self::where('agent_id', $id)
            ->with(['varShareConfig' => function($query){
                $query->select('*');
            }])
            ->orderBy('created_at', 'desc')
            ->first();

        if(!$conf){

            // if no have member set use default
            $games = Games::where('is_active', 1)
                ->with(['typesGame' => function($query){
                    $query->select('*');
                }])
                ->get();

            if(!$games){
                return [];
            }
            $return = [];
            foreach ($games->toArray() as $game){

                $types = [];
                foreach ($game['types_game'] as $type){
                    $types[$type['id']] = 0;
                }

                $return[$game['id']] = $types;

            }

            return $return;
        }

        $conf = $conf->toArray();
        $return = [];
        foreach ($conf['var_share_config'] as $value){
            $return[$value['game_id']] = json_decode($value['values'], true);
        }

        return $return;

    }

}
