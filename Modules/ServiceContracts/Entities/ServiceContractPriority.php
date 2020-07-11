<?php

namespace Modules\ServiceContracts\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Entities\CachableModel;

/**
 * Modules\ServiceContracts\Entities\ServiceContractPriority
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel disableCache()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\ServiceContracts\Entities\ServiceContractPriority onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ServiceContracts\Entities\ServiceContractPriority whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ServiceContracts\Entities\ServiceContractPriority whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ServiceContracts\Entities\ServiceContractPriority whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ServiceContracts\Entities\ServiceContractPriority whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ServiceContracts\Entities\ServiceContractPriority whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel withCacheCooldownSeconds($seconds)
 * @method static \Illuminate\Database\Query\Builder|\Modules\ServiceContracts\Entities\ServiceContractPriority withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\ServiceContracts\Entities\ServiceContractPriority withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ServiceContracts\Entities\ServiceContractPriority newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ServiceContracts\Entities\ServiceContractPriority newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ServiceContracts\Entities\ServiceContractPriority query()
 */
class ServiceContractPriority extends CachableModel
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
    public $table = 'service_contracts_dict_priority';

    public $fillable = [
        'name',
    ];


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
}
