<?php

namespace Modules\Products\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Entities\CachableModel;

/**
 * Modules\Products\Entities\ProductType
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel disableCache()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Products\Entities\ProductType onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\ProductType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\ProductType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\ProductType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\ProductType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\ProductType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel withCacheCooldownSeconds($seconds)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Products\Entities\ProductType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Products\Entities\ProductType withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\ProductType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\ProductType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Products\Entities\ProductType query()
 */
class ProductType extends CachableModel
{
    use SoftDeletes;


    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
    public $table = 'products_dict_type';

    public $fillable = [
        'name',
    ];


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
}
