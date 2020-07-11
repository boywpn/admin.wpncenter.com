<?php

namespace Modules\Tickets\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Entities\CachableModel;

/**
 * Modules\Tickets\Entities\TicketCategory
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel disableCache()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Tickets\Entities\TicketCategory onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\TicketCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\TicketCategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\TicketCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\TicketCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\TicketCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel withCacheCooldownSeconds($seconds)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Tickets\Entities\TicketCategory withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Tickets\Entities\TicketCategory withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\TicketCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\TicketCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\TicketCategory query()
 */
class TicketCategory extends CachableModel
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
    public $table = 'tickets_dict_category';

    public $fillable = [
        'name',
    ];


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
}
