<?php

namespace Modules\Calls\Entities;

use Carbon\Carbon;
use Cog\Contracts\Ownership\Ownable;
use Cog\Laravel\Ownership\Traits\HasMorphOwner;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Accounts\Entities\Account;
use Modules\Contacts\Entities\Contact;
use Modules\Leads\Entities\Lead;
use Modules\Platform\Core\Traits\Commentable;

/**
 * Modules\Calls\Entities\Call
 *
 * @property int $id
 * @property string|null $subject
 * @property string|null $phone_number
 * @property string|null $duration
 * @property string|null $owned_by_type
 * @property int|null $owned_by_id
 * @property \Illuminate\Support\Carbon|null $call_date
 * @property int|null $account_id
 * @property int|null $contact_id
 * @property int|null $lead_id
 * @property int|null $company_id
 * @property int|null $direction_id
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Modules\Accounts\Entities\Account|null $account
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Platform\Core\Entities\Comment[] $comments
 * @property-read \Modules\Contacts\Entities\Contact|null $contact
 * @property-read \Modules\Calls\Entities\DirectionType|null $direction
 * @property-read \Modules\Leads\Entities\Lead|null $lead
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $ownedBy
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Calls\Entities\Call onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calls\Entities\Call whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calls\Entities\Call whereCallDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calls\Entities\Call whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calls\Entities\Call whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calls\Entities\Call whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calls\Entities\Call whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calls\Entities\Call whereDirectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calls\Entities\Call whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calls\Entities\Call whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calls\Entities\Call whereLeadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calls\Entities\Call whereNotOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calls\Entities\Call whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calls\Entities\Call whereOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calls\Entities\Call whereOwnedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calls\Entities\Call whereOwnedByType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calls\Entities\Call wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calls\Entities\Call whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calls\Entities\Call whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Calls\Entities\Call withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Calls\Entities\Call withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calls\Entities\Call newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calls\Entities\Call newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calls\Entities\Call query()
 */
class Call extends Model implements Ownable
{

    use SoftDeletes, BelongsToTenants, HasMorphOwner, Commentable;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
    public $table = 'calls';
    public $fillable = [
        'subject',
        'phone_number',
        'duration',
        'call_date',
        'account_id',
        'contact_id',
        'lead_id',
        'company_id',
        'direction_id',
        'notes',
    ];
    protected $mustBeApproved = false;
    protected $dates = ['deleted_at', 'created_at', 'updated_at','call_date'];

    /**
     * Required to proper parse date provided in user date format
     * @param $value
     */
    public function setCallDateAttribute($value)
    {
        $parsed = Carbon::parse($value);

        $this->attributes['call_date'] = $parsed;
    }


    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function direction()
    {
        return $this->belongsTo(DirectionType::class);
    }

    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }



}
