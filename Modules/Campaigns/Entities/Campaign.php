<?php

namespace Modules\Campaigns\Entities;

use Bnb\Laravel\Attachments\HasAttachment;
use Carbon\Carbon;
use Cog\Contracts\Ownership\Ownable;
use Cog\Laravel\Ownership\Traits\HasMorphOwner;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Accounts\Entities\Account;
use Modules\Contacts\Entities\Contact;
use Modules\Deals\Entities\Deal;
use Modules\Leads\Entities\Lead;
use Modules\Platform\Companies\Entities\Company;
use Modules\Platform\Core\Helper\ActivityLogHelper;
use Modules\Platform\Core\Traits\Commentable;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Modules\Campaigns\Entities\Campaign
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $owned_by_type
 * @property int|null $owned_by_id
 * @property string|null $product
 * @property int|null $target_audience
 * @property string|null $expected_close_date
 * @property string|null $sponsor
 * @property int|null $target_size
 * @property int|null $campaign_status_id
 * @property int|null $campaign_type_id
 * @property float|null $budget_cost
 * @property float|null $actual_budget
 * @property int|null $expected_response
 * @property float|null $expected_revenue
 * @property int|null $expected_sales_count
 * @property int|null $actual_sales_count
 * @property int|null $expected_response_count
 * @property int|null $actual_response_count
 * @property float|null $expected_roi
 * @property float|null $actual_roi
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $company_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Accounts\Entities\Account[] $accounts
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activity
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bnb\Laravel\Attachments\Attachment[] $attachments
 * @property-read \Modules\Campaigns\Entities\CampaignStatus|null $campaignStatus
 * @property-read \Modules\Campaigns\Entities\CampaignType|null $campaignType
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Platform\Core\Entities\Comment[] $comments
 * @property-read \Modules\Platform\Companies\Entities\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Contacts\Entities\Contact[] $contacts
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Deals\Entities\Deal[] $deals
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Leads\Entities\Lead[] $leads
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $ownedBy
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Campaigns\Entities\Campaign onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign whereActualBudget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign whereActualResponseCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign whereActualRoi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign whereActualSalesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign whereBudgetCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign whereCampaignStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign whereCampaignTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign whereExpectedCloseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign whereExpectedResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign whereExpectedResponseCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign whereExpectedRevenue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign whereExpectedRoi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign whereExpectedSalesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign whereNotOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign whereOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign whereOwnedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign whereOwnedByType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign whereProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign whereSponsor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign whereTargetAudience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign whereTargetSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Campaigns\Entities\Campaign withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Campaigns\Entities\Campaign withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\Campaign query()
 */
class Campaign extends Model implements Ownable
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
        'product',
        'target_audience',
        'expected_close_date',
        'sponsor',
        'target_size',
        'campaignStatus.name',
        'campaignType.name',
        'budget_cost',
        'actual_budget',
        'expected_response',
        'expected_revenue',
        'expected_sales_count',
        'actual_sales_count',
        'expected_response_count',
        'actual_response_count',
        'expected_roi',
        'actual_roi',
        'notes',
    ];
    public $table = 'campaigns';
    public $fillable = [
        'name',
        'product',
        'target_audience',
        'expected_close_date',
        'sponsor',
        'target_size',
        'campaign_status_id',
        'campaign_type_id',
        'budget_cost',
        'actual_budget',
        'expected_response',
        'expected_revenue',
        'expected_sales_count',
        'actual_sales_count',
        'expected_response_count',
        'actual_response_count',
        'expected_roi',
        'actual_roi',
        'notes',
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
    public function setexpectedCloseDateAttribute($value)
    {
        $parsed = Carbon::parse($value);

        $this->attributes['expected_close_date'] = $parsed;
    }


    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campaignStatus()
    {
        return $this->belongsTo(CampaignStatus::class);
    }


    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campaignType()
    {
        return $this->belongsTo(CampaignType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function leads()
    {
        return $this->belongsToMany(Lead::class)->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function contacts()
    {
        return $this->belongsToMany(Contact::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function deals()
    {
        return $this->belongsToMany(Deal::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function accounts()
    {
        return $this->belongsToMany(Account::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
