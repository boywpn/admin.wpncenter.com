<?php

namespace Modules\Orders\Entities;

use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Companies\Entities\Company;
use Modules\Products\Entities\Product;

/**
 * Modules\Orders\Entities\OrderRow
 *
 * @property int $id
 * @property string|null $product_name
 * @property int $order_id
 * @property float|null $price
 * @property float|null $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $company_id
 * @property-read \Modules\Platform\Companies\Entities\Company|null $company
 * @property-read mixed $line_total
 * @property-read \Modules\Orders\Entities\Order $order
 * @property-read \Modules\Products\Entities\Product $product
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Orders\Entities\OrderRow onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\OrderRow whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\OrderRow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\OrderRow whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\OrderRow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\OrderRow whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\OrderRow wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\OrderRow whereProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\OrderRow whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\OrderRow whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Orders\Entities\OrderRow withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Orders\Entities\OrderRow withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\OrderRow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\OrderRow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Orders\Entities\OrderRow query()
 */
class OrderRow extends Model
{
    use SoftDeletes, BelongsToTenants;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $table = 'orders_rows';

    protected $fillable = [
        'product_name',
        'order_id',
        'price',
        'quantity',
        'vat_id',
        'company_id'
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

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
