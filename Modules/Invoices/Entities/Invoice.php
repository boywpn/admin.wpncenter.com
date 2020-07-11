<?php

namespace Modules\Invoices\Entities;

use Bnb\Laravel\Attachments\HasAttachment;
use Carbon\Carbon;
use Cog\Contracts\Ownership\Ownable;
use Cog\Laravel\Ownership\Traits\HasMorphOwner;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Accounts\Entities\Account;
use Modules\Contacts\Entities\Contact;
use Modules\Orders\Entities\Order;
use Modules\Platform\Companies\Entities\Company;
use Modules\Platform\Core\Helper\ActivityLogHelper;
use Modules\Platform\Core\Traits\Commentable;
use Modules\Platform\Settings\Entities\Currency;
use Modules\Platform\Settings\Entities\Tax;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Modules\Invoices\Entities\Invoice
 *
 * @property int $id
 * @property string|null $invoice_number
 * @property int|null $order_id
 * @property string|null $customer_no
 * @property int|null $contact_id
 * @property int|null $account_id
 * @property string|null $invoice_date
 * @property string|null $due_date
 * @property string|null $owned_by_type
 * @property int|null $owned_by_id
 * @property int|null $invoice_status_id
 * @property string|null $from_company
 * @property string|null $from_tax_number
 * @property string|null $from_street
 * @property string|null $from_city
 * @property string|null $from_state
 * @property string|null $from_country
 * @property string|null $from_zip_code
 * @property string|null $bill_to
 * @property string|null $bill_tax_number
 * @property string|null $bill_street
 * @property string|null $bill_state
 * @property string|null $bill_country
 * @property string|null $bill_zip_code
 * @property string|null $ship_to
 * @property string|null $ship_tax_number
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
 * @property string|null $account_number
 * @property float|null $delivery_cost
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $bill_city
 * @property string|null $ship_city
 * @property int|null $company_id
 * @property-read \Modules\Accounts\Entities\Account|null $account
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activity
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bnb\Laravel\Attachments\Attachment[] $attachments
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Platform\Core\Entities\Comment[] $comments
 * @property-read \Modules\Platform\Companies\Entities\Company|null $company
 * @property-read \Modules\Contacts\Entities\Contact|null $contact
 * @property-read \Modules\Platform\Settings\Entities\Currency|null $currency
 * @property-read mixed $balance_due
 * @property-read mixed $gross_value
 * @property-read string $subtotal
 * @property-read string $tax_value
 * @property-read \Modules\Invoices\Entities\InvoiceStatus|null $invoiceStatus
 * @property-read \Modules\Orders\Entities\Order|null $order
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $ownedBy
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Invoices\Entities\InvoiceRow[] $rows
 * @property-read \Modules\Platform\Settings\Entities\Tax|null $tax
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Invoices\Entities\Invoice onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereBillCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereBillCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereBillState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereBillStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereBillTaxNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereBillTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereBillZipCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereCustomerNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereDeliveryCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereFromCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereFromCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereFromCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereFromState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereFromStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereFromTaxNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereFromZipCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereInvoiceDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereInvoiceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereInvoiceStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereNotOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereOwnedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereOwnedByType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice wherePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereShipCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereShipCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereShipState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereShipStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereShipTaxNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereShipTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereShipZipCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereTaxId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereTermsAndCond($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Invoices\Entities\Invoice withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Invoices\Entities\Invoice withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\Invoice query()
 */
class Invoice extends Model implements Ownable
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
        'invoice_number',
        'order_id',
        'customer_no',
        'contact_id',
        'account_id',
        'invoice_date',
        'due_date',
        'ownedBy.name',
        'invoiceStatus.name',

        'from_tax_number',
        'from_company',
        'from_street',
        'from_city',
        'from_state',
        'from_country',
        'from_zip_code',

        'bill_tax_number',
        'bill_to',
        'bill_street',
        'bill_city',
        'bill_state',
        'bill_country',
        'bill_zip_code',

        'ship_tax_number',
        'ship_to',
        'ship_street',
        'ship_city',
        'ship_state',
        'ship_country',
        'ship_zip_code',
        'terms_and_cond',
        'notes',
    ];
    public $table = 'invoices';
    public $fillable = [
        'invoice_number',
        'order_id',
        'customer_no',
        'contact_id',
        'account_id',
        'invoice_date',
        'due_date',
        'invoice_status_id',

        'from_tax_number',
        'from_company',
        'from_street',
        'from_city',
        'from_state',
        'from_country',
        'from_zip_code',

        'bill_tax_number',
        'bill_to',
        'bill_street',
        'bill_city',
        'bill_state',
        'bill_country',
        'bill_zip_code',

        'ship_tax_number',
        'ship_to',
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
        'account_number',
        'company_id'
    ];
    protected $mustBeApproved = false;
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    protected $appends = ['balanceDue','subtotal','taxValue','grossValue'];

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
    public function setinvoiceDateAttribute($value)
    {
        $parsed = Carbon::parse($value);

        $this->attributes['invoice_date'] = $parsed;
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
    public function invoiceStatus()
    {
        return $this->belongsTo(InvoiceStatus::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function rows()
    {
        return $this->hasMany(InvoiceRow::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
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

        return  round($subtotal, 2);
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
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
