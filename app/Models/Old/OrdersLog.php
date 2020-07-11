<?php

namespace App\Models\Old;

use GeneaLabs\LaravelModelCaching\CachedModel;

class OrdersLog extends CachedModel
{

    protected $connection = 'mysql2';

    protected $primaryKey = 'A_I';

    public $timestamps = false;

    public $table = 'order_log';

    public function member()
    {
        return $this->hasOne(Members::class, 'id', 'member_id');
    }
}
