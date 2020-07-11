<?php

namespace Modules\Documents\Entities;

use Bnb\Laravel\Attachments\HasAttachment;
use Cog\Contracts\Ownership\Ownable;
use Cog\Laravel\Ownership\Traits\HasMorphOwner;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Accounts\Entities\Account;
use Modules\Contacts\Entities\Contact;
use Modules\Leads\Entities\Lead;
use Modules\Platform\Companies\Entities\Company;
use Modules\Platform\Core\Helper\ActivityLogHelper;
use Modules\Platform\Core\Traits\Commentable;
use Modules\ServiceContracts\Entities\ServiceContract;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Modules\Documents\Entities\Document
 *
 * @property int $id
 * @property string $title
 * @property string|null $notes
 * @property int|null $document_type_id
 * @property int|null $document_status_id
 * @property int|null $document_category_id
 * @property string|null $owned_by_type
 * @property int|null $owned_by_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $company_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Accounts\Entities\Account[] $accounts
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activity
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bnb\Laravel\Attachments\Attachment[] $attachments
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Platform\Core\Entities\Comment[] $comments
 * @property-read \Modules\Platform\Companies\Entities\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Contacts\Entities\Contact[] $contacts
 * @property-read \Modules\Documents\Entities\DocumentCategory|null $documentCategory
 * @property-read \Modules\Documents\Entities\DocumentStatus|null $documentStatus
 * @property-read \Modules\Documents\Entities\DocumentType|null $documentType
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Leads\Entities\Lead[] $leads
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $ownedBy
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\ServiceContracts\Entities\ServiceContract[] $serviceContracts
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Documents\Entities\Document onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Documents\Entities\Document whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Documents\Entities\Document whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Documents\Entities\Document whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Documents\Entities\Document whereDocumentCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Documents\Entities\Document whereDocumentStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Documents\Entities\Document whereDocumentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Documents\Entities\Document whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Documents\Entities\Document whereNotOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Documents\Entities\Document whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Documents\Entities\Document whereOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Documents\Entities\Document whereOwnedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Documents\Entities\Document whereOwnedByType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Documents\Entities\Document whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Documents\Entities\Document whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Documents\Entities\Document withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Documents\Entities\Document withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Documents\Entities\Document newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Documents\Entities\Document newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Documents\Entities\Document query()
 */
class Document extends Model implements Ownable
{
    use SoftDeletes, HasMorphOwner, LogsActivity, Commentable, HasAttachment, BelongsToTenants;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const MODULE_NAME = 'documents';

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
    protected static $logAttributes = [
        'title',
        'notes',
        'ownedBy.name',
        'documentStatus.name',
        'documentType.name',
        'documentCategory.name',
    ];
    public $table = 'documents';
    public $fillable = [
        'title',
        'notes',
        'document_type_id',
        'document_status_id',
        'document_category_id',
        'company_id'
    ];
    protected $mustBeApproved = false;
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
    ];

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function documentStatus()
    {
        return $this->belongsTo(DocumentStatus::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function documentCategory()
    {
        return $this->belongsTo(DocumentCategory::class);
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
    public function accounts()
    {
        return $this->belongsToMany(Account::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function serviceContracts()
    {
        return $this->belongsToMany(ServiceContract::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
