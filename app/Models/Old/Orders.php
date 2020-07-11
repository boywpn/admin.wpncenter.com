<?php

namespace App\Models\Old;

use GeneaLabs\LaravelModelCaching\CachedModel;

class Orders extends CachedModel
{

    protected $connection = 'mysql2';

    public $timestamps = false;

    public $table = 'workspace';
}
