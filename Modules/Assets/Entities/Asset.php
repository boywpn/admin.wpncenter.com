<?php

namespace Modules\Assets\Entities;

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
 * Modules\Assets\Entities\Asset
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $model_no
 * @property string|null $tag_number
 * @property string|null $order_number
 * @property string|null $purchase_date
 * @property float|null $purchase_cost
 * @property string|null $owned_by_type
 * @property int|null $owned_by_id
 * @property int|null $asset_status_id
 * @property int|null $asset_category_id
 * @property int|null $asset_manufacturer_id
 * @property int|null $supplier_id
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $contact_id
 * @property int|null $account_id
 * @property int|null $company_id
 * @property-read \Modules\Accounts\Entities\Account|null $account
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activity
 * @property-read \Modules\Assets\Entities\AssetCategory|null $assetCategory
 * @property-read \Modules\Assets\Entities\AssetManufacturer|null $assetManufacturer
 * @property-read \Modules\Assets\Entities\AssetStatus|null $assetStatus
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bnb\Laravel\Attachments\Attachment[] $attachments
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Platform\Core\Entities\Comment[] $comments
 * @property-read \Modules\Platform\Companies\Entities\Company|null $company
 * @property-read \Modules\Contacts\Entities\Contact|null $contact
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $ownedBy
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Assets\Entities\Asset onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\Asset whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\Asset whereAssetCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\Asset whereAssetManufacturerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\Asset whereAssetStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\Asset whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\Asset whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\Asset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\Asset whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\Asset whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\Asset whereModelNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\Asset whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\Asset whereNotOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\Asset whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\Asset whereOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\Asset whereOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\Asset whereOwnedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\Asset whereOwnedByType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\Asset wherePurchaseCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\Asset wherePurchaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\Asset whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\Asset whereTagNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\Asset whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Assets\Entities\Asset withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Assets\Entities\Asset withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\Asset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\Asset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\Asset query()
 */
class Asset extends Model implements Ownable
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
        'model_no',
        'tag_number',
        'order_number',
        'purchase_date',
        'purchase_cost',
        'ownedBy.name',
        'assetStatus.name',
        'assetCategory.name',
        'assetManufacturer.name',
        'notes',
    ];
    public $table = 'assets';
    public $fillable = [
        'name',
        'model_no',
        'tag_number',
        'order_number',
        'purchase_date',
        'purchase_cost',
        'asset_status_id',
        'asset_category_id',
        'asset_manufacturer_id',
        'notes',
        'contact_id',
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
    public function setpurchaseDateAttribute($value)
    {
        $parsed = Carbon::parse($value);

        $this->attributes['purchase_date'] = $parsed;
    }


    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assetStatus()
    {
        return $this->belongsTo(AssetStatus::class);
    }


    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assetCategory()
    {
        return $this->belongsTo(AssetCategory::class);
    }


    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assetManufacturer()
    {
        return $this->belongsTo(AssetManufacturer::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
