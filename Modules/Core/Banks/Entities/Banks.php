<?php

namespace Modules\Core\Banks\Entities;

use GeneaLabs\LaravelModelCaching\CachedModel;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Traits\Commentable;
use Spatie\Activitylog\Traits\LogsActivity;

class Banks extends Model
{
    use SoftDeletes, LogsActivity, Commentable, BelongsToTenants;

    const COLORS = [
        0 => 'bg-red',
        1 => 'bg-green'
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const FORM_REMOVE_CREATE = [
    ];
    const FORM_REMOVE_EDIT = [
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    protected static $logAttributes = [
        'banks.name',
        'username',
        'password',
        'account',
        'number',
        'phone',
        'is_active',
        'notes'
    ];

    public $table = 'core_banks';

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    public static function getSelectOption($filter = true){

        $main = Banks::where('is_active', 1)
            ->leftJoin('banks', 'core_banks.bank_id', '=', 'banks.id')
            ->select(
                'core_banks.*',
                'banks.name as bank_name',
                'banks.code as bank_code'
            )
            ->orderBy('core_banks.account')
            ->orderBy('banks.sort', 'asc')
            ->get();

        $data = [];
        $data_filter = [];
        foreach ($main as $main){
            $data[$main->id] = delMiltiSpace($main->account)." [".$main->bank_code." ".$main->number."]";

            $data_filter[] = [
                'value' => $main->id,
                'label' => delMiltiSpace($main->account)." [".$main->bank_code." ".$main->number."]"
            ];
        }

        return ($filter) ? $data_filter : $data;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function banks()
    {
        return $this->belongsTo(\App\Models\Banks::class,'bank_id');
    }

}
