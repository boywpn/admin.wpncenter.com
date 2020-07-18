<?php

namespace Modules\Report\TransferLogs\Entities;

use Bnb\Laravel\Attachments\HasAttachment;
use GeneaLabs\LaravelModelCaching\CachedModel;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Banks\Entities\Banks;
use Modules\Core\BanksPartners\Entities\BanksPartners;
use Modules\Core\Partners\Entities\Partners;
use Modules\Core\Username\Entities\Username;
use Modules\Member\Members\Entities\Members;
use Modules\Member\Members\Entities\MembersBanks;
use Modules\Platform\Core\Traits\Commentable;
use Modules\Platform\User\Entities\User;
use Spatie\Activitylog\Traits\LogsActivity;

class TransferLogs extends Model
{

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public $table = 'transfers_log';


}
