<?php

namespace App\Models\Trnf;

use GeneaLabs\LaravelModelCaching\CachedModel;
use Illuminate\Database\Eloquent\Model;

class TransferLogs extends Model
{

    protected $connection = 'trnf';

    protected $primaryKey = 'tran_id';

    public $timestamps = false;

    public $table = 'sh_transfer_log';

}
