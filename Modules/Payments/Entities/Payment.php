<?php

namespace Modules\Payments\Entities;

use Bnb\Laravel\Attachments\HasAttachment;
use Carbon\Carbon;
use Cog\Contracts\Ownership\Ownable;
use Cog\Laravel\Ownership\Traits\HasMorphOwner;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Companies\Entities\Company;
use Modules\Platform\Core\Helper\ActivityLogHelper;
use Modules\Platform\Core\Traits\Commentable;
use Modules\Platform\Settings\Entities\Currency;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Modules\Payments\Entities\Payment
 *
 * @property int $id
 * @property string $name
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon $payment_date
 * @property float $amount
 * @property int $income
 * @property int|null $payment_status_id
 * @property int|null $payment_category_id
 * @property int|null $payment_currency_id
 * @property int|null $payment_payment_method_id
 * @property string|null $owned_by_type
 * @property int|null $owned_by_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $company_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activity
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bnb\Laravel\Attachments\Attachment[] $attachments
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Platform\Core\Entities\Comment[] $comments
 * @property-read \Modules\Platform\Companies\Entities\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $ownedBy
 * @property-read \Modules\Payments\Entities\PaymentCategory|null $paymentCategory
 * @property-read \Modules\Platform\Settings\Entities\Currency|null $paymentCurrency
 * @property-read \Modules\Payments\Entities\PaymentPaymentMethod|null $paymentPaymentMethod
 * @property-read \Modules\Payments\Entities\PaymentStatus|null $paymentStatus
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Payments\Entities\Payment onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Payment whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Payment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Payment whereIncome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Payment whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Payment whereNotOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Payment whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Payment whereOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Payment whereOwnedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Payment whereOwnedByType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Payment wherePaymentCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Payment wherePaymentCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Payment wherePaymentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Payment wherePaymentPaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Payment wherePaymentStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Payments\Entities\Payment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Payments\Entities\Payment withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Payments\Entities\Payment query()
 */
class Payment extends Model implements Ownable
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
        'notes',
        'ownedBy.name',
        'payment_date',
        'amount',
        'paymentStatus.name',
        'paymentCategory.name',
        'paymentCurrency.name',
        'paymentPaymentMethod.name'
    ];
    public $table = 'payments';
    public $fillable = [
        'name',
        'notes',
        'payment_date',
        'amount',
        'payment_status_id',
        'payment_category_id',
        'payment_currency_id',
        'payment_payment_method_id',
        'company_id',
        'income'
    ];
    protected $mustBeApproved = false;
    protected $dates = ['deleted_at', 'created_at', 'updated_at', 'payment_date'];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
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
     * Required to proper parse date provided in user date format
     * @param $value
     */
    public function setPaymentDateAttribute($value)
    {
        $parsed = Carbon::parse($value);

        $this->attributes['payment_date'] = $parsed;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentStatus()
    {
        return $this->belongsTo(PaymentStatus::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentCategory()
    {
        return $this->belongsTo(PaymentCategory::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentCurrency()
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentPaymentMethod()
    {
        return $this->belongsTo(PaymentPaymentMethod::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
