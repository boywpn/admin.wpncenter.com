<?php

namespace Modules\Contacts\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Entities\CachableModel;

/**
 * Modules\Contacts\Entities\ContactStatus
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel disableCache()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Contacts\Entities\ContactStatus onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\ContactStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\ContactStatus whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\ContactStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\ContactStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\ContactStatus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel withCacheCooldownSeconds($seconds)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Contacts\Entities\ContactStatus withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Contacts\Entities\ContactStatus withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\ContactStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\ContactStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\ContactStatus query()
 */
class ContactStatus extends CachableModel
{
    use SoftDeletes;

    const COLORS = [
        1 => 'bg-orange',
        2 => 'bg-green',
        3 => 'bg-light-blue',
        4 => 'bg-pink',
        5 => 'bg-red'
    ];


    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
    public $table = 'contacts_dict_status';

    public $fillable = [
        'name',
        'icon',
        'color'
    ];


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
}
