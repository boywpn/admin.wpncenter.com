<?php

namespace Modules\Calls\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Entities\CachableModel;

/**
 * Modules\Calls\Entities\DirectionType
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Calls\Entities\DirectionType onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calls\Entities\DirectionType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calls\Entities\DirectionType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calls\Entities\DirectionType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calls\Entities\DirectionType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calls\Entities\DirectionType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Calls\Entities\DirectionType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Calls\Entities\DirectionType withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calls\Entities\DirectionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calls\Entities\DirectionType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Calls\Entities\DirectionType query()
 */
class DirectionType extends CachableModel
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
    public $table = 'calls_dict_direction';

    public $fillable = [
        'name',
    ];


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];


}
