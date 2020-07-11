<?php

namespace Modules\Member\Members\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Entities\CachableModel;
use Modules\Platform\Core\Traits\FunctionalTrait;

class MembersStatus extends CachableModel
{
    use SoftDeletes, FunctionalTrait;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
    public $table = 'member_members_dict_status';

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
