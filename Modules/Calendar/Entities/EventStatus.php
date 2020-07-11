<?php

namespace Modules\Calendar\Entities;

use Bnb\Laravel\Attachments\HasAttachment;
use Cog\Contracts\Ownership\Ownable;
use Cog\Laravel\Ownership\Traits\HasMorphOwner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Entities\CachableModel;
use Modules\Platform\Core\Helper\ActivityLogHelper;
use Modules\Platform\Core\Traits\Commentable;
use Spatie\Activitylog\Traits\LogsActivity;
use Carbon\Carbon;

/**
 * Modules\Calendar\Entities\EventStatus
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel disableCache()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Calendar\Entities\EventStatus onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\EventStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\EventStatus whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\EventStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\EventStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\EventStatus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel withCacheCooldownSeconds($seconds)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Calendar\Entities\EventStatus withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Calendar\Entities\EventStatus withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\EventStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\EventStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\EventStatus query()
 */
class EventStatus extends CachableModel
{
    use SoftDeletes;

    const NEW = 1;
    const IN_PROGRESS = 2;


    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
    public $table = 'bap_calendar_dict_event_status';

    public $fillable = [
        'name',
    ];


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
}
