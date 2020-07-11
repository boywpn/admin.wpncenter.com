<?php

namespace Modules\Calendar\Entities;

use Bnb\Laravel\Attachments\HasAttachment;
use Cog\Contracts\Ownership\Ownable;
use Cog\Laravel\Ownership\Traits\HasMorphOwner;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Companies\Entities\Company;
use Modules\Platform\Core\Helper\ActivityLogHelper;
use Modules\Platform\Core\Helper\DateHelper;
use Modules\Platform\Core\Traits\Commentable;
use Modules\Platform\User\Entities\User;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Modules\Calendar\Entities\Event
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $owned_by_type
 * @property int|null $owned_by_id
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property int|null $full_day
 * @property string|null $event_color
 * @property int|null $calendar_id
 * @property int|null $event_priority_id
 * @property int|null $event_status_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $description
 * @property int|null $created_by
 * @property int|null $company_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activity
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bnb\Laravel\Attachments\Attachment[] $attachments
 * @property-read \Modules\Calendar\Entities\Calendar|null $calendar
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Platform\Core\Entities\Comment[] $comments
 * @property-read \Modules\Platform\Companies\Entities\Company|null $company
 * @property-read \Modules\Calendar\Entities\EventPriority|null $eventPriority
 * @property-read \Modules\Calendar\Entities\EventStatus|null $eventStatus
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $ownedBy
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Platform\User\Entities\User[] $sharedWith
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Calendar\Entities\Event onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Event whereCalendarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Event whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Event whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Event whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Event whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Event whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Event whereEventColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Event whereEventPriorityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Event whereEventStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Event whereFullDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Event whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Event whereNotOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Event whereOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Event whereOwnedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Event whereOwnedByType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Event whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Calendar\Entities\Event withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Calendar\Entities\Event withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Event query()
 */
class Event extends Model implements Ownable
{
    use SoftDeletes, HasMorphOwner, LogsActivity, Commentable, HasAttachment, BelongsToTenants;

    protected $mustBeApproved = false;


    protected static $logAttributes = [
        'name',
        'ownedBy.name',
        'start_date',
        'end_date',
        'full_day',
        'event_color',
        'calendar_id',
        'eventPriority.name',
        'eventStatus.name',
    ];

    /**
     * @param  Model $model
     * @param  string $attribute
     * @return  array
     */
    protected static function getRelatedModelAttributeValue(Model $model, string $attribute): array
    {
        return ActivityLogHelper::getRelatedModelAttributeValue($model, $attribute);
    }


    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
    public $table = 'bap_calendar_event';

    public $fillable = [
        'name',
        'start_date',
        'end_date',
        'full_day',
        'event_color',
        'calendar_id',
        'event_priority_id',
        'event_status_id',
        'description',
        'owned_by_id',
        'owned_by_type',
        'created_by',
        'company_id'
    ];


    protected $dates = ['start_date', 'end_date', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * Required to proper parse date provided in user date format
     * @param  $value
     */
    public function setstartDateAttribute($value)
    {
        $value = DateHelper::formatDateTimeToUTC($value);

        $this->attributes['start_date'] = $value;
    }


    /**
     * Required to proper parse date provided in user date format
     * @param  $value
     */
    public function setendDateAttribute($value)
    {
        $value = DateHelper::formatDateTimeToUTC($value);

        $this->attributes['end_date'] = $value;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }

    /**
     * Shared With users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sharedWith()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function eventPriority()
    {
        return $this->belongsTo(EventPriority::class);
    }


    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function eventStatus()
    {
        return $this->belongsTo(EventStatus::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
