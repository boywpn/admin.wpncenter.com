<?php

namespace Modules\Accounts\Entities;

use Bnb\Laravel\Attachments\HasAttachment;
use Cog\Contracts\Ownership\Ownable;
use Cog\Laravel\Ownership\Traits\HasMorphOwner;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Assets\Entities\Asset;
use Modules\Calls\Entities\Call;
use Modules\Campaigns\Entities\Campaign;
use Modules\Contacts\Entities\Contact;
use Modules\Deals\Entities\Deal;
use Modules\Documents\Entities\Document;
use Modules\Invoices\Entities\Invoice;
use Modules\Orders\Entities\Order;
use Modules\Platform\Companies\Entities\Company;
use Modules\Platform\Core\Helper\ActivityLogHelper;
use Modules\Platform\Core\Traits\Commentable;
use Modules\Quotes\Entities\Quote;
use Modules\ServiceContracts\Entities\ServiceContract;
use Modules\Tickets\Entities\Ticket;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Modules\Accounts\Entities\Account
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $owned_by_type
 * @property int|null $owned_by_id
 * @property string|null $website
 * @property string|null $account_number
 * @property float|null $annual_revenue
 * @property int|null $employees
 * @property int|null $account_type_id
 * @property int|null $account_industry_id
 * @property int|null $account_rating_id
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $secondary_email
 * @property string|null $fax
 * @property string|null $skype_id
 * @property string|null $street
 * @property string|null $city
 * @property string|null $state
 * @property string|null $country
 * @property string|null $zip_code
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $tax_number
 * @property int|null $company_id
 * @property-read \Modules\Accounts\Entities\AccountIndustry|null $accountIndustry
 * @property-read \Modules\Accounts\Entities\AccountRating|null $accountRating
 * @property-read \Modules\Accounts\Entities\AccountType|null $accountType
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activity
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Assets\Entities\Asset[] $assets
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bnb\Laravel\Attachments\Attachment[] $attachments
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Campaigns\Entities\Campaign[] $campaigns
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Platform\Core\Entities\Comment[] $comments
 * @property-read \Modules\Platform\Companies\Entities\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Contacts\Entities\Contact[] $contacts
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Deals\Entities\Deal[] $deals
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Documents\Entities\Document[] $documents
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Invoices\Entities\Invoice[] $invoices
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Orders\Entities\Order[] $orders
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $ownedBy
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Quotes\Entities\Quote[] $quotes
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\ServiceContracts\Entities\ServiceContract[] $serviceContracts
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Tickets\Entities\Ticket[] $tickets
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Accounts\Entities\Account onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account whereAccountIndustryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account whereAccountRatingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account whereAccountTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account whereAnnualRevenue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account whereEmployees($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account whereFax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account whereNotOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account whereOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account whereOwnedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account whereOwnedByType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account whereSecondaryEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account whereSkypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account whereTaxNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account whereZipCode($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Accounts\Entities\Account withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Accounts\Entities\Account withoutTrashed()
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Calls\Entities\Call[] $calls
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\Account query()
 */
class Account extends Model implements Ownable
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
        'tax_number',
        'ownedBy.name',
        'website',
        'account_number',
        'annual_revenue',
        'employees',
        'accountType.name',
        'accountIndustry.name',
        'accountRating.name',
        'phone',
        'email',
        'secondary_email',
        'fax',
        'skype_id',
        'street',
        'city',
        'state',
        'country',
        'zip_code',
        'notes',

    ];
    public $table = 'accounts';
    public $fillable = [
        'name',
        'tax_number',
        'website',
        'account_number',
        'annual_revenue',
        'employees',
        'account_type_id',
        'account_industry_id',
        'account_rating_id',
        'phone',
        'email',
        'secondary_email',
        'fax',
        'skype_id',
        'street',
        'city',
        'state',
        'country',
        'zip_code',
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
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accountType()
    {
        return $this->belongsTo(AccountType::class);
    }


    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accountIndustry()
    {
        return $this->belongsTo(AccountIndustry::class);
    }


    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accountRating()
    {
        return $this->belongsTo(AccountRating::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
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
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function serviceContracts()
    {
        return $this->hasMany(ServiceContract::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function documents()
    {
        return $this->belongsToMany(Document::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
