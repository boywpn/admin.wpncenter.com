<?php

namespace Modules\Core\Username\Entities;

use GeneaLabs\LaravelModelCaching\CachedModel;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Boards\Entities\Boards;
use Modules\Core\Boards\Entities\BoardsUsers;
use Modules\Core\Games\Entities\Games;
use Modules\Member\Members\Entities\Members;
use Modules\Platform\Core\Traits\Commentable;
use Spatie\Activitylog\Traits\LogsActivity;

class ViewCheckUsername extends Model
{

    public $table = 'check_username';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

}
