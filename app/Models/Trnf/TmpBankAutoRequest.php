<?php

namespace App\Models\Trnf;

use GeneaLabs\LaravelModelCaching\CachedModel;
use Illuminate\Database\Eloquent\Model;

class TmpBankAutoRequest extends Model
{
    protected $connection = 'trnf';

    public $timestamps = true;

    public $table = 'tmp_auto_request';
}
