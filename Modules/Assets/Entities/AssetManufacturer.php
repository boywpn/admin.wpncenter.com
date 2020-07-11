<?php

namespace Modules\Assets\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Entities\CachableModel;

/**
 * Modules\Assets\Entities\AssetManufacturer
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel disableCache()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Assets\Entities\AssetManufacturer onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\AssetManufacturer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\AssetManufacturer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\AssetManufacturer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\AssetManufacturer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\AssetManufacturer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel withCacheCooldownSeconds($seconds)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Assets\Entities\AssetManufacturer withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Assets\Entities\AssetManufacturer withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\AssetManufacturer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\AssetManufacturer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\AssetManufacturer query()
 */
class AssetManufacturer extends CachableModel
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
    public $table = 'assets_dict_manufacturer';

    public $fillable = [
        'name',
    ];


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
}
