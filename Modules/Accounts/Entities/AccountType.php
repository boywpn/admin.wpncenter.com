<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Entities\CachableModel;

/**
 * Modules\Accounts\Entities\AccountType
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel disableCache()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Accounts\Entities\AccountType onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\AccountType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\AccountType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\AccountType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\AccountType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\AccountType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel withCacheCooldownSeconds($seconds)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Accounts\Entities\AccountType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Accounts\Entities\AccountType withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\AccountType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\AccountType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\AccountType query()
 */
class AccountType extends CachableModel
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
    public $table = 'accounts_dict_type';

    public $fillable = [
        'name',
    ];


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
}
