<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\CachedModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class ApiAccessLogs extends CachedModel
{

//    use SoftDeletes, LogsActivity;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $table = 'api_access_logs';

}
