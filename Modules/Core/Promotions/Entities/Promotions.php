<?php

namespace Modules\Core\Promotions\Entities;

use GeneaLabs\LaravelModelCaching\CachedModel;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Partners\Entities\Partners;
use Modules\Platform\Core\Traits\Commentable;
use Spatie\Activitylog\Traits\LogsActivity;

class Promotions extends Model
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
        'promotionsPartner.name',
        'name',
        'title',
        'description',
        'percent',
        'amount',
        'notes',
        'is_active',
        'is_front',
        'have_ref',
    ];

    public $table = 'core_promotions';

    public $fillable = [

    ];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function promotionsPartner()
    {
        return $this->belongsTo(Partners::class, 'partner_id');
    }

    public static function getPromotionValue($id, $amount){

        $promotion = Promotions::find($id)->toArray();

        $pro_value = 0;
        $pro_percent = $promotion['percent'];
        $pro_amount = $promotion['amount'];

        if(!empty($promotion['max_deposit'])){
            if($amount > $promotion['max_deposit']){
                $amount = $promotion['max_deposit'];
            }
        }

        if(empty($promotion['min_deposit'])){
            if(!empty($pro_percent)){
                $pro_value = $amount * ($pro_percent / 100);
            }else{
                if(!empty($pro_amount)){
                    $pro_value = $pro_amount;
                }
            }
        }else{
            $min_deposit = $promotion['min_deposit'];
            if($amount >= $min_deposit){
                if(!empty($pro_percent)){
                    $pro_value = $amount * ($pro_percent / 100);
                }else{
                    if(!empty($pro_amount)){
                        $pro_value = $pro_amount;
                    }
                }
            }
        }

        if(!empty($promotion['max_value'])){
            if($pro_value > $promotion['max_value']){
                $pro_value = $promotion['max_value'];
            }
        }

        return $pro_value;

    }

}
