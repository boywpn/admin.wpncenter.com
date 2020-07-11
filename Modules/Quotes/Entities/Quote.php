<?php

namespace Modules\Quotes\Entities;

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
use Modules\Platform\Settings\Entities\Currency;
use Modules\Platform\Settings\Entities\Tax;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Modules\Quotes\Entities\Quote
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $owned_by_type
 * @property int|null $owned_by_id
 * @property string|null $valid_unitl
 * @property string|null $shipping
 * @property int|null $quote_stage_id
 * @property int|null $quote_carrier_id
 * @property string|null $street
 * @property string|null $state
 * @property string|null $country
 * @property string|null $zip_code
 * @property string|null $notes
 * @property float|null $discount
 * @property int|null $currency_id
 * @property int|null $tax_id
 * @property float|null $delivery_cost
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $contact_id
 * @property int|null $account_id
 * @property string|null $city
 * @property float|null $amount
 * @property int|null $company_id
 * @property-read \Modules\Accounts\Entities\Account|null $account
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activity
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bnb\Laravel\Attachments\Attachment[] $attachments
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Platform\Core\Entities\Comment[] $comments
 * @property-read \Modules\Platform\Companies\Entities\Company|null $company
 * @property-read \Modules\Contacts\Entities\Contact|null $contact
 * @property-read \Modules\Platform\Settings\Entities\Currency|null $currency
 * @property-read mixed $gross_value
 * @property-read string $subtotal
 * @property-read string $tax_value
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $ownedBy
 * @property-read \Modules\Quotes\Entities\QuoteCarrier|null $quoteCarrier
 * @property-read \Modules\Quotes\Entities\QuoteStage|null $quoteStage
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Quotes\Entities\QuoteRow[] $rows
 * @property-read \Modules\Platform\Settings\Entities\Tax|null $tax
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Quotes\Entities\Quote onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\Quote whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\Quote whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\Quote whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\Quote whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\Quote whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\Quote whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\Quote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\Quote whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\Quote whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\Quote whereDeliveryCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\Quote whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\Quote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\Quote whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\Quote whereNotOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\Quote whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\Quote whereOwnedBy(\Cog\Contracts\Ownership\CanBeOwner $owner)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\Quote whereOwnedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\Quote whereOwnedByType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\Quote whereQuoteCarrierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\Quote whereQuoteStageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\Quote whereShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\Quote whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\Quote whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\Quote whereTaxId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\Quote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\Quote whereValidUnitl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\Quote whereZipCode($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Quotes\Entities\Quote withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Quotes\Entities\Quote withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\Quote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\Quote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\Quote query()
 */
class Quote extends Model implements Ownable
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
        'valid_unitl',
        'shipping',
        'quoteStage.name',
        'quoteCarrier.name',
        'street',
        'city',
        'state',
        'country',
        'zip_code',
        'notes',
    ];
    public $table = 'quotes';
    public $fillable = [
        'name',
        'valid_unitl',
        'shipping',
        'quote_stage_id',
        'quote_carrier_id',
        'street',
        'city',
        'state',
        'country',
        'zip_code',
        'notes',
        'contact_id',
        'account_id',


        'discount',
        'delivery_cost',

        'tax_id',
        'currency_id',
        'company_id'

    ];
    protected $mustBeApproved = false;

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
    protected $appends = ['subtotal', 'taxValue', 'grossValue'];

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
    public function setvalidUnitlAttribute($value)
    {
        $parsed = Carbon::parse($value);

        $this->attributes['valid_unitl'] = $parsed;
    }

    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function quoteStage()
    {
        return $this->belongsTo(QuoteStage::class);
    }

    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function quoteCarrier()
    {
        return $this->belongsTo(QuoteCarrier::class);
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

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function rows()
    {
        return $this->hasMany(QuoteRow::class);
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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
