<?php

namespace Modules\Vendors\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Entities\CachableModel;

/**
 * Modules\Vendors\Entities\VendorCategory
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel disableCache()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Vendors\Entities\VendorCategory onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\VendorCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\VendorCategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\VendorCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\VendorCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\VendorCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel withCacheCooldownSeconds($seconds)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Vendors\Entities\VendorCategory withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Vendors\Entities\VendorCategory withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\VendorCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\VendorCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Vendors\Entities\VendorCategory query()
 */
class VendorCategory extends CachableModel
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
    public $table = 'vendors_dict_category';

    public $fillable = [
        'name',
    ];


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
}
