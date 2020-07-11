<?php

namespace Modules\Quotes\Entities;

use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Companies\Entities\Company;
use Modules\Products\Entities\Product;

/**
 * Modules\Quotes\Entities\QuoteRow
 *
 * @property int $id
 * @property string|null $product_name
 * @property int $quote_id
 * @property float|null $price
 * @property float|null $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $company_id
 * @property int|null $product_id
 * @property-read \Modules\Platform\Companies\Entities\Company|null $company
 * @property-read mixed $line_total
 * @property-read \Modules\Products\Entities\Product|null $product
 * @property-read \Modules\Quotes\Entities\Quote $quote
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Quotes\Entities\QuoteRow onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\QuoteRow whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\QuoteRow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\QuoteRow whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\QuoteRow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\QuoteRow wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\QuoteRow whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\QuoteRow whereProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\QuoteRow whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\QuoteRow whereQuoteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\QuoteRow whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Quotes\Entities\QuoteRow withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Quotes\Entities\QuoteRow withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\QuoteRow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\QuoteRow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\QuoteRow query()
 */
class QuoteRow extends Model
{
    use SoftDeletes, BelongsToTenants;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $table = 'quotes_rows';

    protected $fillable = [
        'product_name',
        'quote_id',
        'price',
        'quantity',
        'vat_id',
        'company_id',
        'product_id'
    ];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    protected $appends = ['lineTotal'];


    /**
     * Get Line Attribute
     * @return mixed
     */
    public function getLineTotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
