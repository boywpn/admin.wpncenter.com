<?php

namespace Modules\Products\Entities;

use Bnb\Laravel\Attachments\HasAttachment;
use Cog\Contracts\Ownership\Ownable;
use Cog\Laravel\Ownership\Traits\HasMorphOwner;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Contacts\Entities\Contact;
use Modules\Leads\Entities\Lead;
use Modules\Platform\Companies\Entities\Company;
use Modules\Platform\Core\Helper\ActivityLogHelper;
use Modules\Platform\Core\Traits\Commentable;
use Modules\Vendors\Entities\Vendor;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Modules\Products\Entities\Product
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $part_number
 * @property string|null $vendor_part_number
 * @property string|null $product_sheet
 * @property string|null $website
 * @property string|null $serial_no
 * @property float|null $price
 * @property string|null $owned_by_type
 * @property int|null $owned_by_id
 * @property int|null $vendor_id
 * @property int|null $product_type_id
 * @property int|null $product_category_id
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $company_id
 * @property string|null $image_path
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activity
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bnb\Laravel\Attachments\Attachment[] $attachments
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Platform\Core\Entities\Comment[] $comments
 * @property-read \Modules\Platform\Companies\Entities\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Contacts\Entities\Contact[] $contacts
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Leads\Entities\Lead[] $leads
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $ownedBy
 * @property-read \Modules\Products\Entities\ProductCategory|null $productCategory
 * @property-read \Modules\Products\Entities\ProductType|null $productType
 * @property-read \Modules\Vendors\Entities\Vendor|null $vendor
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Products\Entities\Product onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\Product whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\Product whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\Product whereNotOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\Product whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\Product whereOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\Product whereOwnedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\Product whereOwnedByType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\Product wherePartNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\Product whereProductCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\Product whereProductSheet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\Product whereProductTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\Product whereSerialNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\Product whereVendorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\Product whereVendorPartNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\Product whereWebsite($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Products\Entities\Product withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Products\Entities\Product withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\Product query()
 */
class Product extends Model implements Ownable
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
        'part_number',
        'vendor_part_number',
        'product_sheet',
        'website',
        'serial_no',
        'price',
        'ownedBy.name',
        'vendor_id',
        'productType.name',
        'productCategory.name',
        'notes',
    ];
    public $table = 'products';
    public $fillable = [
        'name',
        'part_number',
        'vendor_part_number',
        'product_sheet',
        'website',
        'serial_no',
        'price',
        'vendor_id',
        'product_type_id',
        'product_category_id',
        'notes',
        'image_path',
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
    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }


    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function leads()
    {
        return $this->belongsToMany(Lead::class);
    }

    public function priceList()
    {
        return $this->hasMany(PriceList::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function contacts()
    {
        return $this->belongsToMany(Contact::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
