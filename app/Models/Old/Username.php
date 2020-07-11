<?php

namespace App\Models\Old;

use GeneaLabs\LaravelModelCaching\CachedModel;
use Illuminate\Database\Eloquent\Model;

class Username extends Model
{

    protected $connection = 'mysql2';

    public $timestamps = false;

    public $table = 'game_user';

}
