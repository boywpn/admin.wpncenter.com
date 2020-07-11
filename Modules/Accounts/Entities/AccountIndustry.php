<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Entities\CachableModel;

/**
 * Modules\Accounts\Entities\AccountIndustry
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel disableCache()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Accounts\Entities\AccountIndustry onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\AccountIndustry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\AccountIndustry whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\AccountIndustry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\AccountIndustry whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\AccountIndustry whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel withCacheCooldownSeconds($seconds)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Accounts\Entities\AccountIndustry withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Accounts\Entities\AccountIndustry withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\AccountIndustry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\AccountIndustry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\AccountIndustry query()
 */
class AccountIndustry extends CachableModel
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
    public $table = 'accounts_dict_industry';

    public $fillable = [
        'name',
    ];


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
}
