<?php

namespace Modules\Products\Entities;

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


class PriceList extends Model implements Ownable
{
    use SoftDeletes, HasMorphOwner, LogsActivity, Commentable, HasAttachment, BelongsToTenants;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public static function boot()
    {
        parent::boot();

        static::saved(function (PriceList $priceList) {

            if ($priceList->product->priceList()->count() > 0) {

                $priceBook = $priceList->product->priceList()->first();

                $priceList->product->price = $priceBook->price;
                $priceList->product->save();
            }
        });
    }

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
    protected static $logAttributes = [
        'name',
        'price',
        'product.name',
        'ownedBy.name',
    ];
    public $table = 'price_list';
    public $fillable = [
        'name',
        'price',
        'product_id',
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

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
