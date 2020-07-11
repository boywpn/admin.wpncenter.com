<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\CachedModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class AgentTransfersLog extends CachedModel
{

//    use SoftDeletes, LogsActivity;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $table = 'agent_transfers_log';

}
