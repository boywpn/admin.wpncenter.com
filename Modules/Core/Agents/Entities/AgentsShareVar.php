<?php

namespace Modules\Core\Agents\Entities;

use GeneaLabs\LaravelModelCaching\CachedModel;
use Modules\Core\Games\Entities\Games;

class AgentsShareVar extends CachedModel
{

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $table = 'core_agents_share_var';

    protected $dates = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function varShareConfig()
    {
        return $this->belongsTo(AgentsShare::class, 'conf_id');
    }

    public function varGame()
    {
        return $this->belongsTo(Games::class, 'game_id');
    }

}
