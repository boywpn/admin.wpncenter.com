<?php

namespace Modules\Report\Winloss\Entities;

use GeneaLabs\LaravelModelCaching\CachedModel;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Agents\Entities\Agents;
use Modules\Core\Boards\Entities\Boards;
use Modules\Core\Games\Entities\Games;
use Modules\Core\Games\Entities\GamesTypes;
use Modules\Core\Username\Entities\Username;
use Modules\Member\Members\Entities\Members;
use Modules\Platform\Core\Traits\Commentable;
use Modules\Platform\Core\Traits\FunctionalTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class ViewWinlossMember extends CachedModel
{

    protected $connection = 'db_report';

    public $table = 'view_report_winloss_member';

}
