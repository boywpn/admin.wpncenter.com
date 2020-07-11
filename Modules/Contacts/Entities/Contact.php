<?php

namespace Modules\Contacts\Entities;

use Bnb\Laravel\Attachments\HasAttachment;
use Cog\Contracts\Ownership\Ownable;
use Cog\Laravel\Ownership\Traits\HasMorphOwner;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Modules\Accounts\Entities\Account;
use Modules\Assets\Entities\Asset;
use Modules\Calls\Entities\Call;
use Modules\Campaigns\Entities\Campaign;
use Modules\ContactEmails\Entities\ContactEmail;
use Modules\ContactEmails\Service\ContactEmailService;
use Modules\Deals\Entities\Deal;
use Modules\Documents\Entities\Document;
use Modules\Invoices\Entities\Invoice;
use Modules\Orders\Entities\Order;
use Modules\Platform\Companies\Entities\Company;
use Modules\Platform\Core\Helper\ActivityLogHelper;
use Modules\Platform\Core\Traits\Commentable;
use Modules\Products\Entities\Product;
use Modules\Quotes\Entities\Quote;
use Modules\Tickets\Entities\Ticket;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Modules\Contacts\Entities\Contact
 *
 * @property int $id
 * @property string|null $owned_by_type
 * @property int|null $owned_by_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $full_name
 * @property string|null $job_title
 * @property string|null $department
 * @property int|null $contact_status_id
 * @property int|null $contact_source_id
 * @property string|null $phone
 * @property string|null $mobile
 * @property string|null $email
 * @property string|null $secondary_email
 * @property string|null $fax
 * @property string|null $assistant_name
 * @property string|null $assistant_phone
 * @property string|null $street
 * @property string|null $state
 * @property string|null $country
 * @property string|null $zip_code
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $account_id
 * @property string|null $city
 * @property int|null $company_id
 * @property-read \Modules\Accounts\Entities\Account|null $account
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activity
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Assets\Entities\Asset[] $assets
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bnb\Laravel\Attachments\Attachment[] $attachments
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Campaigns\Entities\Campaign[] $campaigns
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Platform\Core\Entities\Comment[] $comments
 * @property-read \Modules\Platform\Companies\Entities\Company|null $company
 * @property-read \Modules\Contacts\Entities\ContactSource|null $contactSource
 * @property-read \Modules\Contacts\Entities\ContactStatus|null $contactStatus
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Deals\Entities\Deal[] $deals
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Documents\Entities\Document[] $documents
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Invoices\Entities\Invoice[] $invoices
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Orders\Entities\Order[] $orders
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $ownedBy
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Products\Entities\Product[] $products
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Quotes\Entities\Quote[] $quotes
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Tickets\Entities\Ticket[] $tickets
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Contacts\Entities\Contact onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereAssistantName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereAssistantPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereContactSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereContactStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereFax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereJobTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereNotOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereOwnedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereOwnedByType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereSecondaryEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereZipCode($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Contacts\Entities\Contact withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Contacts\Entities\Contact withoutTrashed()
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Calls\Entities\Call[] $calls
 * @property string|null $tags
 * @property string|null $profile_image
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Contacts\Entities\ContactEmail[] $emails
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereProfileImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\Contact whereTags($value)
 */
class Contact extends Model implements Ownable
{
    use SoftDeletes, HasMorphOwner, LogsActivity, Commentable, HasAttachment, BelongsToTenants;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const STATUS_NEW = 1;
    const STATUS_APPROVED = 2;
    const STATUS_IN_PROGRESS = 3;
    const STATUS_TEST = 4;
    const STATUS_TRASH = 5;


    public static function boot()
    {
        parent::boot();

        static::saving(function (Contact $contact) {
            $fullName = $contact->first_name . ' ' . $contact->last_name;
            $contact->full_name = $fullName;

        });

        static::saved(function (Contact $contact) {

            $contactEmailService = App::make(ContactEmailService::class);
            $contactEmailService->manageContactEmail($contact);
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
        'ownedBy.name',
        'first_name',
        'last_name',
        'full_name',
        'job_title',
        'department',
        'contactStatus.name',
        'contactSource.name',
        'phone',
        'mobile',
        'email',
        'secondary_email',
        'fax',
        'assistant_name',
        'assistant_phone',
        'street',
        'city',
        'state',
        'country',
        'zip_code',
        'notes',
        'tags'
    ];
    public $table = 'contacts';
    public $fillable = [
        'first_name',
        'last_name',
        'full_name',
        'job_title',
        'department',
        'contact_status_id',
        'contact_source_id',
        'account_id',
        'phone',
        'mobile',
        'email',
        'secondary_email',
        'fax',
        'assistant_name',
        'assistant_phone',
        'street',
        'city',
        'state',
        'country',
        'zip_code',
        'notes',
        'tags',
        'profile_image',
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
    public function contactStatus()
    {
        return $this->belongsTo(ContactStatus::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function calls()
    {
        return $this->hasMany(Call::class);
    }


    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contactSource()
    {
        return $this->belongsTo(ContactSource::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class);
    }

    public function deals()
    {
        return $this->belongsToMany(Deal::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contactEmails()
    {
        return $this->hasMany(ContactEmail::class);
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
    public function documents()
    {
        return $this->belongsToMany(Document::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
