<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\CachedModel;

class Banks extends CachedModel
{
    
    public $table = 'banks';

    public static function getSelectOption(){
        $data = Banks::all();

        $return = [];
        foreach ($data as $data){
            $return[] = [
                'value' => $data->id,
                'label' => $data->name
            ];
        }

        return $return;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function coreBanks()
    {
        return $this->hasMany(\Modules\Core\Banks\Entities\Banks::class, 'bank_id');
    }

}
