<?php

namespace Modules\ContactRequests\Entities;

use Bnb\Laravel\Attachments\HasAttachment;
use Cog\Contracts\Ownership\Ownable;
use Cog\Laravel\Ownership\Traits\HasMorphOwner;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Helper\ActivityLogHelper;
use Modules\Platform\Core\Traits\Commentable;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Modules\ContactRequests\Entities\ContactRequest
 *
 * @property int $id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $organization_name
 * @property string|null $phone_number
 * @property string|null $email
 * @property string|null $other_contact_method
 * @property string|null $custom_subject
 * @property string|null $contact_date
 * @property string|null $next_contact_date
 * @property string|null $owned_by_type
 * @property int|null $owned_by_id
 * @property int|null $status_id
 * @property int|null $preferred_id
 * @property int|null $contact_reason_id
 * @property int|null $company_id
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activity
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bnb\Laravel\Attachments\Attachment[] $attachments
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Platform\Core\Entities\Comment[] $comments
 * @property-read \Modules\ContactRequests\Entities\ContactReason|null $contactReason
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $ownedBy
 * @property-read \Modules\ContactRequests\Entities\PreferredContactMethod|null $preferred
 * @property-read \Modules\ContactRequests\Entities\ContactRequestStatus|null $status
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\ContactRequests\Entities\ContactRequest onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequest whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequest whereContactDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequest whereContactReasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequest whereCustomSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequest whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequest whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequest whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequest whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequest whereNextContactDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequest whereNotOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequest whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequest whereOrganizationName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequest whereOtherContactMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequest whereOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequest whereOwnedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequest whereOwnedByType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequest wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequest wherePreferredId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequest whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\ContactRequests\Entities\ContactRequest withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\ContactRequests\Entities\ContactRequest withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequest query()
 */
class ContactRequest extends Model implements Ownable
{

    use SoftDeletes, BelongsToTenants, HasMorphOwner, LogsActivity, Commentable, HasAttachment;

    protected $mustBeApproved = false;


    protected static $logAttributes = [
        'first_name',
        'last_name',
        'organization_name',
        'phone_number',
        'email',
        'other_contact_method',
        'custom_subject',
        'contact_date',
        'next_contact_date',
        'ownedBy.name',
        'status.name',
        'preferred.name',
        'contactReason.name',
        'notes',
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
    public $table = 'contact_request';

    public $fillable = [
        'first_name',
        'last_name',
        'organization_name',
        'phone_number',
        'email',
        'other_contact_method',
        'custom_subject',
        'contact_date',
        'next_contact_date',
        'status_id',
        'preferred_id',
        'contact_reason_id',
        'company_id',
        'notes',
    ];


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];


    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(ContactRequestStatus::class);
    }


    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function preferred()
    {
        return $this->belongsTo(PreferredContactMethod::class);
    }


    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contactReason()
    {
        return $this->belongsTo(ContactReason::class);
    }


}
