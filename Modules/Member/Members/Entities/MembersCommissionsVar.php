<?php

namespace Modules\Member\Members\Entities;

use App\Models\Banks;
use GeneaLabs\LaravelModelCaching\CachedModel;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Games\Entities\Games;
use Modules\Platform\Core\Traits\Commentable;
use Spatie\Activitylog\Traits\LogsActivity;

class MembersCommissionsVar extends CachedModel
{

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $table = 'member_members_commissions_var';

    protected $dates = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function varCommission()
    {
        return $this->belongsTo(MembersCommissions::class, 'comm_id');
    }

    public function varGame()
    {
        return $this->belongsTo(Games::class, 'game_id');
    }

}
