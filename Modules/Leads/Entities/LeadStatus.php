<?php

namespace Modules\Leads\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Entities\CachableModel;

/**
 * Class LeadStatus
 *
 * @package Modules\Leads\Entities
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel disableCache()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Leads\Entities\LeadStatus onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\LeadStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\LeadStatus whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\LeadStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\LeadStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\LeadStatus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel withCacheCooldownSeconds($seconds)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Leads\Entities\LeadStatus withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Leads\Entities\LeadStatus withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\LeadStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\LeadStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\LeadStatus query()
 */
class LeadStatus extends CachableModel
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
    public $table = 'leads_dict_status';

    public $fillable = [
        'name',
        'icon',
        'color'
    ];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
    ];
}
