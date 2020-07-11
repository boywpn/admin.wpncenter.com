<?php

namespace Modules\Core\Owners\Entities;

use GeneaLabs\LaravelModelCaching\CachedModel;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Traits\Commentable;
use Spatie\Activitylog\Traits\LogsActivity;

class Owners extends Model
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
        'code',
        'phone',
        'note',
        'is_active',
        'is_suspend',
        'api_token',
    ];

    public $table = 'core_owners';

    public $fillable = [
        'name',
        'code',
        'phone',
        'note',
        'is_active',
        'is_suspend',
        'api_token',
    ];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

}
