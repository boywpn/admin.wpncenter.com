<?php

namespace Modules\Wallet\Jobs\Entities;

use GeneaLabs\LaravelModelCaching\CachedModel;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Agents\Entities\Agents;
use Modules\Core\Boards\Entities\Boards;
use Modules\Core\Games\Entities\Games;
use Modules\Core\Username\Entities\Username;
use Modules\Member\Members\Entities\Members;
use Modules\Platform\Core\Traits\Commentable;
use Modules\Platform\Core\Traits\FunctionalTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class Jobs extends Model
{
    use SoftDeletes, LogsActivity, Commentable, FunctionalTrait;

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
        'jobsMember.name',
        'ref',
        'state_id',
        'order_id',
        'amount',
        'type_id',
        'jobsStatus.status_id',
        'callback_at',
        'callback_result',
        'responsed_at',
        'responsed_result',
    ];

    public $table = 'wallet_jobs';

    public $fillable = [

    ];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jobsMember()
    {
        return $this->belongsTo(Members::class, 'member_id');
    }
    public function jobsStatus()
    {
        return $this->belongsTo(JobsStatus::class, 'status_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jobsGame()
    {
        return $this->belongsTo(Games::class, 'game_id');
    }
    public function jobsVoid()
    {
        return $this->belongsTo(Jobs::class, 'void_from_id');
    }

}
