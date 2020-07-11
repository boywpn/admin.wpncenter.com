<?php

namespace Modules\Leads\Entities;

use Bnb\Laravel\Attachments\HasAttachment;
use Carbon\Carbon;
use Cog\Contracts\Ownership\Ownable;
use Cog\Laravel\Ownership\Traits\HasMorphOwner;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Modules\Calls\Entities\Call;
use Modules\Campaigns\Entities\Campaign;
use Modules\Documents\Entities\Document;
use Modules\LeadEmails\Entities\LeadEmail;
use Modules\LeadEmails\Service\LeadEmailService;
use Modules\Platform\Companies\Entities\Company;
use Modules\Platform\Core\Helper\ActivityLogHelper;
use Modules\Platform\Core\Traits\Commentable;
use Modules\Products\Entities\Product;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class Lead
 *
 * @package Modules\Leads\Entities
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $full_name
 * @property string|null $email
 * @property string|null $fax
 * @property string|null $annual_revenue
 * @property string|null $website
 * @property string|null $no_of_employees
 * @property string|null $skype
 * @property string|null $lead_company
 * @property string|null $job_title
 * @property string|null $phone
 * @property string|null $mobile
 * @property string|null $secondary_email
 * @property string|null $twitter
 * @property string|null $facebook
 * @property string|null $description
 * @property string|null $addr_street
 * @property string|null $addr_state
 * @property string|null $addr_country
 * @property string|null $addr_city
 * @property string|null $addr_zip
 * @property int|null $lead_status_id
 * @property int|null $lead_source_id
 * @property int|null $lead_industry_id
 * @property int|null $lead_rating_id
 * @property string|null $owned_by_type
 * @property int|null $owned_by_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $company_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activity
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bnb\Laravel\Attachments\Attachment[] $attachments
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Campaigns\Entities\Campaign[] $campaigns
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Platform\Core\Entities\Comment[] $comments
 * @property-read \Modules\Platform\Companies\Entities\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Documents\Entities\Document[] $documents
 * @property-read \Modules\Leads\Entities\LeadIndustry|null $leadIndustry
 * @property-read \Modules\Leads\Entities\LeadRating|null $leadRating
 * @property-read \Modules\Leads\Entities\LeadSource|null $leadSource
 * @property-read \Modules\Leads\Entities\LeadStatus|null $leadStatus
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $ownedBy
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Products\Entities\Product[] $products
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Leads\Entities\Lead onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereAddrCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereAddrCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereAddrState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereAddrStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereAddrZip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereAnnualRevenue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereFax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereJobTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereLeadCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereLeadIndustryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereLeadRatingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereLeadSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereLeadStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereNoOfEmployees($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereNotOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereOwnedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereOwnedByType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereSecondaryEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereSkype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereTwitter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereWebsite($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Leads\Entities\Lead withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Leads\Entities\Lead withoutTrashed()
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $capture_date
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Calls\Entities\Call[] $calls
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead whereCaptureDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\Lead query()
 */
class Lead extends Model implements Ownable
{
    use SoftDeletes, HasMorphOwner, LogsActivity, Commentable, HasAttachment, BelongsToTenants;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const STATUS_NEW = 1;

    public static function boot()
    {
        parent::boot();

        static::saving(function(Lead $lead){
            $fullName = $lead->first_name . ' ' . $lead->last_name;
            $lead->full_name = $fullName;
        });

        static::saved(function (Lead $lead) {

            $leadEmailService = App::make(LeadEmailService::class);
            $leadEmailService->manageLeadEmail($lead);
        });
    }

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
    protected static $logAttributes = [
        'first_name',
        'last_name',
        'full_name',
        'email',
        'fax',
        'annual_revenue',
        'website',
        'no_of_employees',
        'skype',
        'company_name',
        'job_title',
        'phone',
        'mobile',
        'secondary_email',
        'twitter',
        'facebook',
        'description',
        'addr_street',
        'addr_state',
        'addr_country',
        'addr_city',
        'addr_zip',
        'ownedBy.name',
        'leadStatus.name',
        'leadSource.name',
        'leadIndustry.name',
        'leadRating.name',
        'capture_date'
    ];
    public $table = 'leads';

    public $fillable = [
        'first_name',
        'last_name',
        'full_name',
        'email',
        'fax',
        'annual_revenue',
        'website',
        'no_of_employees',
        'skype',
        'company_name',
        'job_title',
        'phone',
        'mobile',
        'secondary_email',
        'twitter',
        'facebook',
        'description',
        'addr_street',
        'addr_state',
        'addr_country',
        'addr_city',
        'addr_zip',
        'lead_status_id',
        'lead_source_id',
        'lead_industry_id',
        'lead_rating_id',
        'company_id',
        'lead_company',
        'capture_date'
    ];

    protected $mustBeApproved = false;

    protected $dates = ['deleted_at', 'created_at', 'updated_at','capture_date'];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
    ];

    /**
     * Required to proper parse date provided in user date format
     * @param $value
     */
    public function setCaptureDateAttribute($value)
    {
        $parsed = Carbon::parse($value);

        $this->attributes['capture_date'] = $parsed;
    }

    /**
     * @param Model $model
     * @param string $attribute
     * @return array
     */
    protected static function getRelatedModelAttributeValue(Model $model, string $attribute): array
    {
        return ActivityLogHelper::getRelatedModelAttributeValue($model, $attribute);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function calls()
    {
        return $this->hasMany(Call::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leadEmails()
    {
        return $this->hasMany(LeadEmail::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function leadStatus()
    {
        return $this->belongsTo(LeadStatus::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function leadSource()
    {
        return $this->belongsTo(LeadSource::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function leadIndustry()
    {
        return $this->belongsTo(LeadIndustry::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function leadRating()
    {
        return $this->belongsTo(LeadRating::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function documents()
    {
        return $this->belongsToMany(Document::class)->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
