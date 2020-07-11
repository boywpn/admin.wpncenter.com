<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Entities\CachableModel;

/**
 * Modules\Accounts\Entities\AccountRating
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel disableCache()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Accounts\Entities\AccountRating onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\AccountRating whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\AccountRating whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\AccountRating whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\AccountRating whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\AccountRating whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel withCacheCooldownSeconds($seconds)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Accounts\Entities\AccountRating withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Accounts\Entities\AccountRating withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\AccountRating newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\AccountRating newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Accounts\Entities\AccountRating query()
 */
class AccountRating extends CachableModel
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
    public $table = 'accounts_dict_rating';

    public $fillable = [
        'name',
    ];


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
}
