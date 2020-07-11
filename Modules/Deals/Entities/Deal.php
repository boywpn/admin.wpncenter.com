<?php

namespace Modules\Deals\Entities;

use Bnb\Laravel\Attachments\HasAttachment;
use Carbon\Carbon;
use Cog\Contracts\Ownership\Ownable;
use Cog\Laravel\Ownership\Traits\HasMorphOwner;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Accounts\Entities\Account;
use Modules\Campaigns\Entities\Campaign;
use Modules\Contacts\Entities\Contact;
use Modules\Orders\Entities\Order;
use Modules\Platform\Companies\Entities\Company;
use Modules\Platform\Core\Helper\ActivityLogHelper;
use Modules\Platform\Core\Traits\Commentable;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Modules\Deals\Entities\Deal
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $owned_by_type
 * @property int|null $owned_by_id
 * @property float|null $amount
 * @property string|null $closing_date
 * @property float|null $probability
 * @property float|null $expected_revenue
 * @property string|null $next_step
 * @property int|null $deal_stage_id
 * @property int|null $deal_business_type_id
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $account_id
 * @property int|null $company_id
 * @property-read \Modules\Accounts\Entities\Account|null $account
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activity
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bnb\Laravel\Attachments\Attachment[] $attachments
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Campaigns\Entities\Campaign[] $campaigns
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Platform\Core\Entities\Comment[] $comments
 * @property-read \Modules\Platform\Companies\Entities\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Contacts\Entities\Contact[] $contacts
 * @property-read \Modules\Deals\Entities\DealBusinessType|null $dealBusinessType
 * @property-read \Modules\Deals\Entities\DealStage|null $dealStage
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Orders\Entities\Order[] $orders
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $ownedBy
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Deals\Entities\Deal onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Deals\Entities\Deal whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Deals\Entities\Deal whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Deals\Entities\Deal whereClosingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Deals\Entities\Deal whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Deals\Entities\Deal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Deals\Entities\Deal whereDealBusinessTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Deals\Entities\Deal whereDealStageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Deals\Entities\Deal whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Deals\Entities\Deal whereExpectedRevenue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Deals\Entities\Deal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Deals\Entities\Deal whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Deals\Entities\Deal whereNextStep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Deals\Entities\Deal whereNotOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Deals\Entities\Deal whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Deals\Entities\Deal whereOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Deals\Entities\Deal whereOwnedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Deals\Entities\Deal whereOwnedByType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Deals\Entities\Deal whereProbability($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Deals\Entities\Deal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Deals\Entities\Deal withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Deals\Entities\Deal withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Deals\Entities\Deal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Deals\Entities\Deal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Deals\Entities\Deal query()
 */
class Deal extends Model implements Ownable
{
    use SoftDeletes, HasMorphOwner, LogsActivity, Commentable, HasAttachment, BelongsToTenants;

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
        'name',
        'ownedBy.name',
        'amount',
        'closing_date',
        'probability',
        'expected_revenue',
        'next_step',
        'dealStage.name',
        'dealBusinessType.name',
        'notes',
    ];
    public $table = 'deals';
    public $fillable = [
        'name',
        'amount',
        'closing_date',
        'probability',
        'expected_revenue',
        'next_step',
        'deal_stage_id',
        'deal_business_type_id',
        'notes',
        'account_id',
        'company_id'
    ];
    protected $mustBeApproved = false;



    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    /**
     * @param  Model $model
     * @param  string $attribute
     * @return  array
     */
    protected static function getRelatedModelAttributeValue(Model $model, string $attribute): array
    {
        return ActivityLogHelper::getRelatedModelAttributeValue($model, $attribute);
    }

    /**
     * Required to proper parse date provided in user date format
     * @param  $value
     */
    public function setclosingDateAttribute($value)
    {
        $parsed = Carbon::parse($value);

        $this->attributes['closing_date'] = $parsed;
    }


    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dealStage()
    {
        return $this->belongsTo(DealStage::class);
    }


    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dealBusinessType()
    {
        return $this->belongsTo(DealBusinessType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function contacts()
    {
        return $this->belongsToMany(Contact::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
