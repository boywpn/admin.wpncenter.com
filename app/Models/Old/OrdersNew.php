<?php

namespace App\Models\Old;

use GeneaLabs\LaravelModelCaching\CachedModel;

class OrdersNew extends CachedModel
{

    protected $connection = 'mysql2';

    public $timestamps = false;

    public $table = 'order_new';
}
