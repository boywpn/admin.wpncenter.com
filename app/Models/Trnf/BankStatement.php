<?php

namespace App\Models\Trnf;

use GeneaLabs\LaravelModelCaching\CachedModel;
use Illuminate\Database\Eloquent\Model;

class BankStatement extends Model
{

    protected $connection = 'trnf';

    protected $primaryKey = 'state_id';

    public $timestamps = false;

    public $table = 'ai_bank_statement';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transfer()
    {
        return $this->belongsTo(TransferLogs::class, 'state_tran_id');
    }
}
