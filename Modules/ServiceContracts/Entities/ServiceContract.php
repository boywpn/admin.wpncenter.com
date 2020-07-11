<?php

namespace Modules\ServiceContracts\Entities;

use Bnb\Laravel\Attachments\HasAttachment;
use Carbon\Carbon;
use Cog\Contracts\Ownership\Ownable;
use Cog\Laravel\Ownership\Traits\HasMorphOwner;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Accounts\Entities\Account;
use Modules\Documents\Entities\Document;
use Modules\Platform\Companies\Entities\Company;
use Modules\Platform\Core\Helper\ActivityLogHelper;
use Modules\Platform\Core\Traits\Commentable;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Modules\ServiceContracts\Entities\ServiceContract
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $start_date
 * @property string|null $due_date
 * @property string|null $owned_by_type
 * @property int|null $owned_by_id
 * @property int|null $service_contract_priority_id
 * @property int|null $service_contract_status_id
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $account_id
 * @property int|null $company_id
 * @property-read \Modules\Accounts\Entities\Account|null $account
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activity
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bnb\Laravel\Attachments\Attachment[] $attachments
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Platform\Core\Entities\Comment[] $comments
 * @property-read \Modules\Platform\Companies\Entities\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Documents\Entities\Document[] $documents
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $ownedBy
 * @property-read \Modules\ServiceContracts\Entities\ServiceContractPriority|null $serviceContractPriority
 * @property-read \Modules\ServiceContracts\Entities\ServiceContractStatus|null $serviceContractStatus
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\ServiceContracts\Entities\ServiceContract onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ServiceContracts\Entities\ServiceContract whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ServiceContracts\Entities\ServiceContract whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ServiceContracts\Entities\ServiceContract whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ServiceContracts\Entities\ServiceContract whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ServiceContracts\Entities\ServiceContract whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ServiceContracts\Entities\ServiceContract whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ServiceContracts\Entities\ServiceContract whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ServiceContracts\Entities\ServiceContract whereNotOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ServiceContracts\Entities\ServiceContract whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ServiceContracts\Entities\ServiceContract whereOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ServiceContracts\Entities\ServiceContract whereOwnedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ServiceContracts\Entities\ServiceContract whereOwnedByType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ServiceContracts\Entities\ServiceContract whereServiceContractPriorityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ServiceContracts\Entities\ServiceContract whereServiceContractStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ServiceContracts\Entities\ServiceContract whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ServiceContracts\Entities\ServiceContract whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\ServiceContracts\Entities\ServiceContract withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\ServiceContracts\Entities\ServiceContract withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ServiceContracts\Entities\ServiceContract newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ServiceContracts\Entities\ServiceContract newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ServiceContracts\Entities\ServiceContract query()
 */
class ServiceContract extends Model implements Ownable
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
        'start_date',
        'due_date',
        'ownedBy.name',
        'serviceContractPriority.name',
        'serviceContractStatus.name',
        'notes',
    ];
    public $table = 'service_contracts';
    public $fillable = [
        'name',
        'start_date',
        'due_date',
        'service_contract_priority_id',
        'service_contract_status_id',
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
    public function setstartDateAttribute($value)
    {
        $parsed = Carbon::parse($value);

        $this->attributes['start_date'] = $parsed;
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
    public function serviceContractPriority()
    {
        return $this->belongsTo(ServiceContractPriority::class);
    }


    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function serviceContractStatus()
    {
        return $this->belongsTo(ServiceContractStatus::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
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
