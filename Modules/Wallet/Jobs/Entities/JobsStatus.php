<?php

namespace Modules\Wallet\Jobs\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Entities\CachableModel;
use Modules\Platform\Core\Traits\FunctionalTrait;

class JobsStatus extends CachableModel
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
    public $table = 'wallet_jobs_dict_status';

    public $fillable = [

    ];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
}
