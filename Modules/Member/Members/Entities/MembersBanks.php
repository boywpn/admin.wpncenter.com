<?php

namespace Modules\Member\Members\Entities;

use App\Models\Banks;
use GeneaLabs\LaravelModelCaching\CachedModel;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Platform\Core\Traits\Commentable;
use Spatie\Activitylog\Traits\LogsActivity;

class MembersBanks extends Model
{
    use SoftDeletes, LogsActivity, Commentable, BelongsToTenants;

    const COLORS = [
        0 => 'bg-red',
        1 => 'bg-green'
    ];

    /**
     * For Remove Form
    */
    const FORM_REMOVE_CREATE = [

    ];
    const FORM_REMOVE_EDIT = [
        // 'password'
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

    protected static $logAttributes = [
        'banksBank.name',
        'bank_account',
        'bank_number',
        'is_main',
        'is_active',
        'notes',
    ];

    public $table = 'member_members_banks';

    public $fillable = [

    ];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    public static function getSelectOption(){
        $main = MembersBanks::all();

        $data = [];
        foreach ($main as $main){
            $data[] = [
                'value' => $main->id,
                'label' => $main->bank_number
            ];
        }

        return $data;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function banksMember()
    {
        return $this->belongsTo(Members::class, 'member_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function banksBank()
    {
        return $this->belongsTo(Banks::class, 'bank_id');
    }

}
