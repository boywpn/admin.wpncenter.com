<?php

namespace Modules\Calendar\Entities;

use Cog\Contracts\Ownership\Ownable;
use Cog\Laravel\Ownership\Traits\HasMorphOwner;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Companies\Entities\Company;

/**
 * Modules\Calendar\Entities\Calendar
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $is_public
 * @property string|null $owned_by_type
 * @property int|null $owned_by_id
 * @property string|null $default_view
 * @property int|null $first_day
 * @property string|null $day_start_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $created_by
 * @property int|null $company_id
 * @property-read \Modules\Platform\Companies\Entities\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Calendar\Entities\Event[] $events
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $ownedBy
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Calendar\Entities\Calendar onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Calendar whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Calendar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Calendar whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Calendar whereDayStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Calendar whereDefaultView($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Calendar whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Calendar whereFirstDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Calendar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Calendar whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Calendar whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Calendar whereNotOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Calendar whereOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Calendar whereOwnedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Calendar whereOwnedByType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Calendar whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Calendar\Entities\Calendar withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Calendar\Entities\Calendar withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Calendar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Calendar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calendar\Entities\Calendar query()
 */
class Calendar extends Model implements Ownable
{
    use SoftDeletes, HasMorphOwner, BelongsToTenants;

    const MONT_VIEW = 'month';
    const WEEK_VIEW = 'agendaWeek';
    const DAY_VIEW = 'agendaDay';

    const DEFAULT_DAT_START_AT = '00:00:00';

    const WEEK_START_AT_MONDAY = 1;
    const WEEK_START_AT_SUNDAY = 0;



    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
    public $table = 'bap_calendar';

    public $fillable = [
        'name',
        'is_public',
        'default_view',
        'first_day',
        'day_start_at',
        'created_by',
        'company_id'
    ];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
