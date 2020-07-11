<?php

namespace App\Models\Trnf;

use GeneaLabs\LaravelModelCaching\CachedModel;

class Sms extends CachedModel
{

    protected $connection = 'trnf';

    public $timestamps = false;

    public $table = 'ai_bank_sms';

    protected $primaryKey = 'sms_id';
}
