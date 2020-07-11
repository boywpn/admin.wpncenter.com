<?php

namespace Modules\Campaigns\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Entities\CachableModel;

/**
 * Modules\Campaigns\Entities\CampaignType
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel disableCache()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Campaigns\Entities\CampaignType onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\CampaignType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\CampaignType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\CampaignType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\CampaignType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\CampaignType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel withCacheCooldownSeconds($seconds)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Campaigns\Entities\CampaignType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Campaigns\Entities\CampaignType withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\CampaignType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\CampaignType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Campaigns\Entities\CampaignType query()
 */
class CampaignType extends CachableModel
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
    public $table = 'campaigns_dict_type';

    public $fillable = [
        'name',
    ];


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
}
