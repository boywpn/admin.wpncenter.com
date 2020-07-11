<?php

namespace Modules\Invoices\Entities;

use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Companies\Entities\Company;
use Modules\Products\Entities\PriceList;
use Modules\Products\Entities\Product;

/**
 * Modules\Invoices\Entities\InvoiceRow
 *
 * @property int $id
 * @property string|null $product_name
 * @property int $invoice_id
 * @property float|null $price
 * @property float|null $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $company_id
 * @property-read \Modules\Platform\Companies\Entities\Company|null $company
 * @property-read mixed $line_total
 * @property-read \Modules\Invoices\Entities\Invoice $invoice
 * @property-read \Modules\Products\Entities\Product $product
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Invoices\Entities\InvoiceRow onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\InvoiceRow whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\InvoiceRow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\InvoiceRow whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\InvoiceRow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\InvoiceRow whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\InvoiceRow wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\InvoiceRow whereProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\InvoiceRow whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\InvoiceRow whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Invoices\Entities\InvoiceRow withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Invoices\Entities\InvoiceRow withoutTrashed()
 * @mixin \Eloquent
 * @property int|null $product_id
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\InvoiceRow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\InvoiceRow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\InvoiceRow query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Invoices\Entities\InvoiceRow whereProductId($value)
 */
class InvoiceRow extends Model
{
    use SoftDeletes, BelongsToTenants;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $table = 'invoices_rows';

    protected $fillable = [
        'product_name',
        'invoice_id',
        'price',
        'quantity',
        'vat_id',
        'product_id',
        'price_list_id',
        'company_id',
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

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function priceList()
    {
        return $this->belongsTo(PriceList::class);
    }


    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
