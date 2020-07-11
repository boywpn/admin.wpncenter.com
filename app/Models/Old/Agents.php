<?php

namespace App\Models\Old;

use GeneaLabs\LaravelModelCaching\CachedModel;

class Agents extends CachedModel
{

    protected $connection = 'mysql2';

    public $table = 'agent';

}
