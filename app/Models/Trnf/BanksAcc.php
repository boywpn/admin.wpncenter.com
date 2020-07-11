<?php

namespace App\Models\Trnf;

use GeneaLabs\LaravelModelCaching\CachedModel;
use Illuminate\Database\Eloquent\Model;

class BanksAcc extends Model
{

    protected $connection = 'trnf';

    protected $primaryKey = 'acc_id';

    public $timestamps = false;

    public $table = 'ai_bank_acc';

}
