<?php

namespace Modules\Leads\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Entities\CachableModel;

/**
 * Class LeadSource
 *
 * @package Modules\Leads\Entities
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel disableCache()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Leads\Entities\LeadSource onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\LeadSource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\LeadSource whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\LeadSource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\LeadSource whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\LeadSource whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel withCacheCooldownSeconds($seconds)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Leads\Entities\LeadSource withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Leads\Entities\LeadSource withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\LeadSource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\LeadSource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\LeadSource query()
 */
class LeadSource extends CachableModel
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
    public $table = 'leads_dict_source';

    public $fillable = [
        'name',
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
