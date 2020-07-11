<?php

namespace Modules\Quotes\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Entities\CachableModel;

/**
 * Modules\Quotes\Entities\QuoteStage
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel disableCache()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Quotes\Entities\QuoteStage onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\QuoteStage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\QuoteStage whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\QuoteStage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\QuoteStage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\QuoteStage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel withCacheCooldownSeconds($seconds)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Quotes\Entities\QuoteStage withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Quotes\Entities\QuoteStage withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\QuoteStage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\QuoteStage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quotes\Entities\QuoteStage query()
 */
class QuoteStage extends CachableModel
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
    public $table = 'quotes_dict_stage';

    public $fillable = [
        'name',
    ];


    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
}
