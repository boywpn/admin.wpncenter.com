<?php

namespace Modules\Core\Partners\Entities;

use GeneaLabs\LaravelModelCaching\CachedModel;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Agents\Entities\Agents;
use Modules\Core\Owners\Entities\Owners;
use Modules\Core\Promotions\Entities\Promotions;
use Modules\Platform\Core\Traits\Commentable;
use Spatie\Activitylog\Traits\LogsActivity;

class Partners extends Model
{
    use SoftDeletes, LogsActivity, Commentable, BelongsToTenants;

    const COLORS = [
        0 => 'bg-red',
        1 => 'bg-green'
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
        'name',
        'code',
        'website',
        'phone',
        'note',
        'is_active',
        'api_active',
        'owner_id',
    ];

    public $table = 'core_partners';

    public $fillable = [
        'name',
        'code',
        'website',
        'company_id',
        'is_active',
        'api_active',
        'owner_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
//        'owner_id',
//        'old_id',
//        'api_active',
//        'company_id',
//        'deleted_at'
    ];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

//    public static function getSelectOption(){
//        $main = Partners::all();
//
//        $data = [];
//        foreach ($main as $main){
//            $data[] = [
//                'value' => $main->id,
//                'label' => $main->name
//            ];
//        }
//
//        return $data;
//    }

    public static function getSelectOption($filter = true){

        $main = Partners::all();

        $data = [];
        $data_filter = [];
        foreach ($main as $main){
            $data[$main->id] = $main->name;

            $data_filter[] = [
                'value' => $main->id,
                'label' => $main->name
            ];
        }

        return ($filter) ? $data_filter : $data;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function partnersPromotion()
    {
        return $this->hasMany(Promotions::class, 'partner_id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function agents()
    {
        return $this->hasMany(Agents::class, 'partner_id');
    }


    public function owner()
    {
        return $this->belongsTo(Owners::class);
    }

}
