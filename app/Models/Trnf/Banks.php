<?php

namespace App\Models\Trnf;

use GeneaLabs\LaravelModelCaching\CachedModel;
use Illuminate\Database\Eloquent\Model;

class Banks extends Model
{

    protected $connection = 'trnf';

    protected $primaryKey = 'bank_id';

    public $timestamps = false;

    public $table = 'ai_bank';

}
