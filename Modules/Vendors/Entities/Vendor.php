<?php

namespace Modules\Vendors\Entities;

use Bnb\Laravel\Attachments\HasAttachment;
use Cog\Contracts\Ownership\Ownable;
use Cog\Laravel\Ownership\Traits\HasMorphOwner;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Companies\Entities\Company;
use Modules\Platform\Core\Helper\ActivityLogHelper;
use Modules\Platform\Core\Traits\Commentable;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Modules\Vendors\Entities\Vendor
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $owned_by_type
 * @property int|null $owned_by_id
 * @property int|null $vendor_category_id
 * @property int|null $supplier_id
 * @property string|null $phone
 * @property string|null $mobile
 * @property string|null $email
 * @property string|null $secondary_email
 * @property string|null $fax
 * @property string|null $skype_id
 * @property string|null $street
 * @property string|null $state
 * @property string|null $country
 * @property string|null $zip_code
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $city
 * @property int|null $company_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activity
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bnb\Laravel\Attachments\Attachment[] $attachments
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Platform\Core\Entities\Comment[] $comments
 * @property-read \Modules\Platform\Companies\Entities\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $ownedBy
 * @property-read \Modules\Vendors\Entities\VendorCategory|null $vendorCategory
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Vendors\Entities\Vendor onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\Vendor whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\Vendor whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\Vendor whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\Vendor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\Vendor whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\Vendor whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\Vendor whereFax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\Vendor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\Vendor whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\Vendor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\Vendor whereNotOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\Vendor whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\Vendor whereOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\Vendor whereOwnedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\Vendor whereOwnedByType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\Vendor wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\Vendor whereSecondaryEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\Vendor whereSkypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\Vendor whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\Vendor whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\Vendor whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\Vendor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\Vendor whereVendorCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\Vendor whereZipCode($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Vendors\Entities\Vendor withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Vendors\Entities\Vendor withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\Vendor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\Vendor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\Vendor query()
 */
class Vendor extends Model implements Ownable
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
        'vendorCategory.name',
        'phone',
        'mobile',
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
    public $table = 'vendors';
    public $fillable = [
        'name',
        'vendor_category_id',
        'phone',
        'mobile',
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
    public function vendorCategory()
    {
        return $this->belongsTo(VendorCategory::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
