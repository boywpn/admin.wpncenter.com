<?php

namespace Modules\Tickets\Entities;

use Bnb\Laravel\Attachments\HasAttachment;
use Carbon\Carbon;
use Cog\Contracts\Ownership\Ownable;
use Cog\Laravel\Ownership\Traits\HasMorphOwner;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Accounts\Entities\Account;
use Modules\Contacts\Entities\Contact;
use Modules\Platform\Companies\Entities\Company;
use Modules\Platform\Core\Helper\ActivityLogHelper;
use Modules\Platform\Core\Traits\Commentable;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Modules\Tickets\Entities\Ticket
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $due_date
 * @property string|null $owned_by_type
 * @property int|null $owned_by_id
 * @property int|null $ticket_priority_id
 * @property int|null $ticket_status_id
 * @property int|null $ticket_severity_id
 * @property int|null $ticket_category_id
 * @property string|null $description
 * @property string|null $resolution
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $contact_id
 * @property int|null $account_id
 * @property int|null $company_id
 * @property int|null $parent_id
 * @property-read \Modules\Accounts\Entities\Account|null $account
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activity
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bnb\Laravel\Attachments\Attachment[] $attachments
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Platform\Core\Entities\Comment[] $comments
 * @property-read \Modules\Platform\Companies\Entities\Company|null $company
 * @property-read \Modules\Contacts\Entities\Contact|null $contact
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $ownedBy
 * @property-read \Modules\Tickets\Entities\Ticket|null $parent
 * @property-read \Modules\Tickets\Entities\TicketCategory|null $ticketCategory
 * @property-read \Modules\Tickets\Entities\TicketPriority|null $ticketPriority
 * @property-read \Modules\Tickets\Entities\TicketSeverity|null $ticketSeverity
 * @property-read \Modules\Tickets\Entities\TicketStatus|null $ticketStatus
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Tickets\Entities\Ticket[] $tickets
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Tickets\Entities\Ticket onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\Ticket whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\Ticket whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\Ticket whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\Ticket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\Ticket whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\Ticket whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\Ticket whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\Ticket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\Ticket whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\Ticket whereNotOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\Ticket whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\Ticket whereOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\Ticket whereOwnedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\Ticket whereOwnedByType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\Ticket whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\Ticket whereResolution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\Ticket whereTicketCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\Ticket whereTicketPriorityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\Ticket whereTicketSeverityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\Ticket whereTicketStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\Ticket whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Tickets\Entities\Ticket withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Tickets\Entities\Ticket withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\Ticket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\Ticket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\Ticket query()
 */
class Ticket extends Model implements Ownable
{
    use SoftDeletes, HasMorphOwner, LogsActivity, Commentable, HasAttachment, BelongsToTenants;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const STATUS_NEW = 1;

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
    protected static $logAttributes = [
        'name',
        'due_date',
        'ownedBy.name',
        'ticketPriority.name',
        'ticketStatus.name',
        'ticketSeverity.name',
        'ticketCategory.name',
        'description',
        'resolution',
        'notes',
    ];
    public $table = 'tickets';
    public $fillable = [
        'name',
        'due_date',
        'ticket_priority_id',
        'ticket_status_id',
        'ticket_severity_id',
        'ticket_category_id',
        'description',
        'resolution',
        'notes',
        'contact_id',
        'account_id',
        'parent_id'
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
    public function setdueDateAttribute($value)
    {
        $parsed = Carbon::parse($value);

        $this->attributes['due_date'] = $parsed;
    }


    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticketPriority()
    {
        return $this->belongsTo(TicketPriority::class);
    }


    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticketStatus()
    {
        return $this->belongsTo(TicketStatus::class);
    }


    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticketSeverity()
    {
        return $this->belongsTo(TicketSeverity::class);
    }


    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticketCategory()
    {
        return $this->belongsTo(TicketCategory::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function parent()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class,'parent_id','id');
    }


}
