<?php

namespace Modules\Campaigns\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Entities\CachableModel;

/**
 * Modules\Campaigns\Entities\CampaignStatus
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel disableCache()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Campaigns\Entities\CampaignStatus onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\CampaignStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\CampaignStatus whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\CampaignStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\CampaignStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\CampaignStatus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel withCacheCooldownSeconds($seconds)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Campaigns\Entities\CampaignStatus withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Campaigns\Entities\CampaignStatus withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\CampaignStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\CampaignStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\CampaignStatus query()
 */
class CampaignStatus extends CachableModel
{
    use SoftDeletes;

    const COLORS = [
        1 => 'bg-grey',
        2 => 'bg-green',
        3 => 'bg-brown',
        4 => 'bg-light-green',
        5 => 'bg-red'
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
    public $table = 'campaigns_dict_status';

    public $fillable = [
        'name',
        'icon',
        'color'
    ];


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
}
