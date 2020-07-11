<?php

namespace Modules\Tickets\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Entities\CachableModel;

/**
 * Modules\Tickets\Entities\TicketStatus
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel disableCache()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Tickets\Entities\TicketStatus onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\TicketStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\TicketStatus whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\TicketStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\TicketStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\TicketStatus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel withCacheCooldownSeconds($seconds)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Tickets\Entities\TicketStatus withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Tickets\Entities\TicketStatus withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\TicketStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\TicketStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\TicketStatus query()
 */
class TicketStatus extends CachableModel
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
    public $table = 'tickets_dict_status';

    public $fillable = [
        'name',
    ];


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
}
