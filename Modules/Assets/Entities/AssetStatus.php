<?php

namespace Modules\Assets\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Entities\CachableModel;

/**
 * Modules\Assets\Entities\AssetStatus
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel disableCache()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Assets\Entities\AssetStatus onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\AssetStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\AssetStatus whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\AssetStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\AssetStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\AssetStatus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel withCacheCooldownSeconds($seconds)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Assets\Entities\AssetStatus withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Assets\Entities\AssetStatus withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\AssetStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\AssetStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Assets\Entities\AssetStatus query()
 */
class AssetStatus extends CachableModel
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
    public $table = 'assets_dict_status';

    public $fillable = [
        'name',
    ];


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
}
