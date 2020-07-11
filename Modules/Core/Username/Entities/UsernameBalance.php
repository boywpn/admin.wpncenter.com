<?php

namespace Modules\Core\Username\Entities;

use GeneaLabs\LaravelModelCaching\CachedModel;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Boards\Entities\Boards;
use Modules\Member\Members\Entities\Members;
use Modules\Platform\Core\Traits\Commentable;
use Spatie\Activitylog\Traits\LogsActivity;

class UsernameBalance extends CachedModel
{
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $table = 'core_username_balance';

    protected $dates = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usernameBoard()
    {
        return $this->belongsTo(Username::class,'username_id');
    }

}
