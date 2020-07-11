<?php

namespace Modules\Leads\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Entities\CachableModel;

/**
 * Class LeadRating
 *
 * @package Modules\Leads\Entities
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel disableCache()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Leads\Entities\LeadRating onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\LeadRating whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\LeadRating whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\LeadRating whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\LeadRating whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\LeadRating whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel withCacheCooldownSeconds($seconds)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Leads\Entities\LeadRating withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Leads\Entities\LeadRating withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\LeadRating newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\LeadRating newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Leads\Entities\LeadRating query()
 */
class LeadRating extends CachableModel
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
    public $table = 'leads_dict_rating';

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
