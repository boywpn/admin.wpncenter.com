<?php

namespace App\Models\Old;

use GeneaLabs\LaravelModelCaching\CachedModel;

class Banks extends CachedModel
{

    protected $connection = 'mysql2';

    public $table = 'bank_all';

}
