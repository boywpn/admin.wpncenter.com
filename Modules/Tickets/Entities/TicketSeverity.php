<?php

namespace Modules\Tickets\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Entities\CachableModel;

/**
 * Modules\Tickets\Entities\TicketSeverity
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel disableCache()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Tickets\Entities\TicketSeverity onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\TicketSeverity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\TicketSeverity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\TicketSeverity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\TicketSeverity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\TicketSeverity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel withCacheCooldownSeconds($seconds)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Tickets\Entities\TicketSeverity withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Tickets\Entities\TicketSeverity withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\TicketSeverity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\TicketSeverity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Tickets\Entities\TicketSeverity query()
 */
class TicketSeverity extends CachableModel
{
    use SoftDeletes;

    const COLORS = [
        1 => 'bg-light-green',
        2 => 'bg-green',
        3 => 'bg-orange',
        4 => 'bg-red'
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
    public $table = 'tickets_dict_severity';

    public $fillable = [
        'name',
    ];


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
}
