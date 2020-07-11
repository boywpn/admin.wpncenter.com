<?php

namespace App\Models\Old;

use GeneaLabs\LaravelModelCaching\CachedModel;
use Illuminate\Database\Eloquent\Model;

class BonusLog extends Model
{

    protected $connection = 'mysql2';

    public $timestamps = false;

    public $table = 'member_bonus_log';

    public $fillable = ['status'];
}
