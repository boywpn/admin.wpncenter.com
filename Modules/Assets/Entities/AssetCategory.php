<?php

namespace Modules\Assets\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Entities\CachableModel;

/**
 * Modules\Assets\Entities\AssetCategory
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel disableCache()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Assets\Entities\AssetCategory onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\AssetCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\AssetCategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\AssetCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\AssetCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\AssetCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel withCacheCooldownSeconds($seconds)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Assets\Entities\AssetCategory withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Assets\Entities\AssetCategory withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\AssetCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\AssetCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\AssetCategory query()
 */
class AssetCategory extends CachableModel
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
    public $table = 'assets_dict_category';

    public $fillable = [
        'name',
    ];


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
}
