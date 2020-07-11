<?php

namespace Modules\Documents\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Entities\CachableModel;

/**
 * Modules\Documents\Entities\DocumentType
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel disableCache()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Documents\Entities\DocumentType onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Documents\Entities\DocumentType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Documents\Entities\DocumentType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Documents\Entities\DocumentType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Documents\Entities\DocumentType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Documents\Entities\DocumentType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Platform\Core\Entities\CachableModel withCacheCooldownSeconds($seconds)
 * @method static \Illuminate\Database\Query\Builder|\Modules\Documents\Entities\DocumentType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Modules\Documents\Entities\DocumentType withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Documents\Entities\DocumentType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Documents\Entities\DocumentType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Documents\Entities\DocumentType query()
 */
class DocumentType extends CachableModel
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
    public $table = 'documents_dict_type';

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
