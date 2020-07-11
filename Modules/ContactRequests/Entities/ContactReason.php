<?php

namespace Modules\ContactRequests\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Entities\CachableModel;

/**
 * Modules\ContactRequests\Entities\ContactReason
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\ContactRequests\Entities\ContactReason onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactReason whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactReason whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactReason whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactReason whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactReason whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\ContactRequests\Entities\ContactReason withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\ContactRequests\Entities\ContactReason withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactReason newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactReason newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactReason query()
 */
class ContactReason extends CachableModel
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
    public $table = 'contact_request_dict_contact_reason';

    public $fillable = [
        'name',
    ];


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];


}
