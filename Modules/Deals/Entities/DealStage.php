<?php

namespace Modules\Deals\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Entities\CachableModel;

/**
 * Modules\Deals\Entities\DealStage
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel disableCache()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Deals\Entities\DealStage onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Deals\Entities\DealStage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Deals\Entities\DealStage whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Deals\Entities\DealStage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Deals\Entities\DealStage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Deals\Entities\DealStage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel withCacheCooldownSeconds($seconds)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Deals\Entities\DealStage withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Deals\Entities\DealStage withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Deals\Entities\DealStage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Deals\Entities\DealStage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Deals\Entities\DealStage query()
 */
class DealStage extends CachableModel
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
    public $table = 'deals_dict_stage';

    public $fillable = [
        'name',
    ];


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
}
