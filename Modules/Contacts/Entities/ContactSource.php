<?php

namespace Modules\Contacts\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Entities\CachableModel;

/**
 * Modules\Contacts\Entities\ContactSource
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel disableCache()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Contacts\Entities\ContactSource onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\ContactSource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\ContactSource whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\ContactSource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\ContactSource whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\ContactSource whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel withCacheCooldownSeconds($seconds)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Contacts\Entities\ContactSource withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Contacts\Entities\ContactSource withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\ContactSource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\ContactSource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Contacts\Entities\ContactSource query()
 */
class ContactSource extends CachableModel
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
    public $table = 'contacts_dict_source';

    public $fillable = [
        'name',
    ];


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
}
