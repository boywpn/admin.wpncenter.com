<?php

namespace Modules\Orders\Entities;

use Bnb\Laravel\Attachments\HasAttachment;
use Carbon\Carbon;
use Cog\Contracts\Ownership\Ownable;
use Cog\Laravel\Ownership\Traits\HasMorphOwner;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Accounts\Entities\Account;
use Modules\Contacts\Entities\Contact;
use Modules\Deals\Entities\Deal;
use Modules\Platform\Companies\Entities\Company;
use Modules\Platform\Core\Helper\ActivityLogHelper;
use Modules\Platform\Core\Traits\Commentable;
use Modules\Platform\Settings\Entities\Currency;
use Modules\Platform\Settings\Entities\Tax;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Modules\Orders\Entities\Order
 *
 * @property int $id
 * @property string|null $order_number
 * @property string|null $carrier_number
 * @property int|null $deal_id
 * @property string|null $customer_no
 * @property int|null $contact_id
 * @property int|null $account_id
 * @property string|null $purchase_order
 * @property string|null $due_date
 * @property string|null $order_date
 * @property string|null $owned_by_type
 * @property int|null $owned_by_id
 * @property int|null $order_status_id
 * @property int|null $order_carrier_id
 * @property string|null $bill_street
 * @property string|null $bill_state
 * @property string|null $bill_country
 * @property string|null $bill_zip_code
 * @property string|null $ship_street
 * @property string|null $ship_state
 * @property string|null $ship_country
 * @property string|null $ship_zip_code
 * @property string|null $terms_and_cond
 * @property string|null $notes
 * @property float|null $discount
 * @property int|null $currency_id
 * @property int|null $tax_id
 * @property float|null $paid
 * @property float|null $delivery_cost
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $bill_city
 * @property string|null $ship_city
 * @property string|null $bill_to
 * @property string|null $bill_tax_number
 * @property string|null $ship_to
 * @property string|null $ship_tax_number
 * @property int|null $company_id
 * @property-read \Modules\Accounts\Entities\Account|null $account
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activity
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bnb\Laravel\Attachments\Attachment[] $attachments
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Platform\Core\Entities\Comment[] $comments
 * @property-read \Modules\Platform\Companies\Entities\Company|null $company
 * @property-read \Modules\Contacts\Entities\Contact|null $contact
 * @property-read \Modules\Platform\Settings\Entities\Currency|null $currency
 * @property-read \Modules\Deals\Entities\Deal|null $deal
 * @property-read mixed $balance_due
 * @property-read mixed $gross_value
 * @property-read string $subtotal
 * @property-read string $tax_value
 * @property-read \Modules\Orders\Entities\OrderCarrier|null $orderCarrier
 * @property-read \Modules\Orders\Entities\OrderStatus|null $orderStatus
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $ownedBy
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Orders\Entities\OrderRow[] $rows
 * @property-read \Modules\Platform\Settings\Entities\Tax|null $tax
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Orders\Entities\Order onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereBillCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereBillCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereBillState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereBillStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereBillTaxNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereBillTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereBillZipCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereCarrierNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereCustomerNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereDealId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereDeliveryCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereNotOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereOrderCarrierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereOrderDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereOrderStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereOwnedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereOwnedByType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order wherePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order wherePurchaseOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereShipCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereShipCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereShipState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereShipStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereShipTaxNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereShipTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereShipZipCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereTaxId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereTermsAndCond($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Orders\Entities\Order withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Orders\Entities\Order withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\Order query()
 */
class Order extends Model implements Ownable
{
    use SoftDeletes, HasMorphOwner, LogsActivity, Commentable, HasAttachment, BelongsToTenants;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const STATUS_CREATED = 1;
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
    protected static $logAttributes = [
        'order_number',
        'carrier_number',
        'deal_id',
        'customer_no',
        'contact_id',
        'account_id',
        'purchase_order',
        'due_date',
        'order_date',
        'ownedBy.name',
        'orderStatus.name',
        'orderCarrier.name',
        'bill_street',
        'bill_city',
        'bill_state',
        'bill_country',
        'bill_zip_code',
        'ship_street',
        'ship_city',
        'ship_state',
        'ship_country',
        'ship_zip_code',
        'terms_and_cond',
        'notes',
    ];
    public $table = 'orders';
    public $fillable = [
        'order_number',
        'carrier_number',
        'deal_id',
        'customer_no',
        'contact_id',
        'account_id',
        'purchase_order',
        'due_date',
        'order_date',
        'order_status_id',
        'order_carrier_id',
        'bill_street',
        'bill_city',
        'bill_state',
        'bill_country',
        'bill_zip_code',
        'ship_street',
        'ship_city',
        'ship_state',
        'ship_country',
        'ship_zip_code',
        'terms_and_cond',
        'notes',

        'discount',
        'delivery_cost',
        'tax_id',
        'currency_id',
        'paid',

        'bill_to',
        'bill_tax_number',
        'ship_to',
        'ship_tax_number',
        'company_id'
    ];
    protected $appends = ['balanceDue', 'subtotal', 'taxValue', 'grossValue'];
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
     * Required to proper parse date provided in user date format
     * @param  $value
     */
    public function setOrderDateAttribute($value)
    {
        $parsed = Carbon::parse($value);

        $this->attributes['order_date'] = $parsed;
    }


    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class);
    }


    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orderCarrier()
    {
        return $this->belongsTo(OrderCarrier::class);
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

    /**
     * Get Balance Due Value
     * @return mixed
     */
    public function getBalanceDueAttribute()
    {
        return $this->grossValue - $this->paid;
    }

    public function getGrossValueAttribute()
    {
        return $this->subtotal + $this->delivery_cost - $this->discount + $this->taxValue;
    }

    /**
     * Get Subtotal Value
     * @return string
     */
    public function getSubtotalAttribute()
    {
        $subtotal = 0;

        foreach ($this->rows()->get() as $row) {
            $subtotal += $row->lineTotal;
        }

        return round($subtotal, 2);
    }

    public function rows()
    {
        return $this->hasMany(OrderRow::class);
    }

    /**
     * Get Tax Value
     * @return string
     */
    public function getTaxValueAttribute()
    {
        if ($this->tax != null) {
            $subtotal = $this->subtotal;

            return round((($subtotal + $this->delivery_cost - $this->discount) * $this->tax->tax_value), 2);
        }
        return 0;
    }


    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
