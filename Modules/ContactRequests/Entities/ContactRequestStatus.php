<?php

namespace Modules\ContactRequests\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Entities\CachableModel;

/**
 * Modules\ContactRequests\Entities\ContactRequestStatus
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\ContactRequests\Entities\ContactRequestStatus onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequestStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequestStatus whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequestStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequestStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequestStatus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\ContactRequests\Entities\ContactRequestStatus withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\ContactRequests\Entities\ContactRequestStatus withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequestStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequestStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\ContactRequestStatus query()
 */
class ContactRequestStatus extends CachableModel
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
    public $table = 'contact_request_dict_contact_status';

    public $fillable = [
        'name',
    ];


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];


}
