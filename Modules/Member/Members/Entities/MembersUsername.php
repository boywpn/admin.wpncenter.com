<?php

namespace Modules\Member\Members\Entities;

use GeneaLabs\LaravelModelCaching\CachedModel;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Agents\Entities\Agents;
use Modules\Platform\Core\Traits\Commentable;
use Spatie\Activitylog\Traits\LogsActivity;

class MembersUsername extends Model
{

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $table = 'member_members_username';

}
