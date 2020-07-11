<?php

namespace Modules\ContactRequests\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Entities\CachableModel;

/**
 * Modules\ContactRequests\Entities\PreferredContactMethod
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\ContactRequests\Entities\PreferredContactMethod onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\PreferredContactMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\PreferredContactMethod whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\PreferredContactMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\PreferredContactMethod whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\PreferredContactMethod whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Modules\ContactRequests\Entities\PreferredContactMethod withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\ContactRequests\Entities\PreferredContactMethod withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\PreferredContactMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\PreferredContactMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ContactRequests\Entities\PreferredContactMethod query()
 */
class PreferredContactMethod extends CachableModel
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
    public $table = 'contact_request_dict_contact_method';

    public $fillable = [
        'name',
    ];


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];


}
