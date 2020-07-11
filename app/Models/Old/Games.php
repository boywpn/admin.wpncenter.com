<?php

namespace App\Models\Old;

use GeneaLabs\LaravelModelCaching\CachedModel;

class Games extends CachedModel
{

    protected $connection = 'mysql2';

    public $table = 'game_web';

}
