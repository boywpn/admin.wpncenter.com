<?php

namespace App\Models\Trnf;

use GeneaLabs\LaravelModelCaching\CachedModel;
use Illuminate\Database\Eloquent\Model;

class TmpBankAuto extends Model
{
    protected $connection = 'trnf';

    public $timestamps = false;

    public $table = 'tmp_bank';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tmpBank()
    {
        return $this->belongsTo(Banks::class,'bank_id');
    }

    public static function getList(){

        $state = self::leftJoin('ai_bank', 'tmp_bank.bank_id', 'ai_bank.bank_id')
            ->where('id', 632418)
            // ->where('tmp_bank.status', 0)
            // ->whereRaw('DATE(tmp_bank.created_at) = ?', ['2019-08-25'])
            // ->limit(30)
            ->orderBy('tmp_bank.id', 'asc')
            ->select(
                'tmp_bank.*',
                'ai_bank.bank_code',
                'ai_bank.bank_name'
            )
            ->get();

        return $state->toArray();

    }

    public static function getById($id = []){

        $state = self::leftJoin('ai_bank', 'tmp_bank.bank_id', 'ai_bank.bank_id')
            ->whereIn('id', $id)
            ->select(
                'tmp_bank.*',
                'ai_bank.bank_code',
                'ai_bank.bank_name'
            )
            ->get();

        return $state->toArray();

    }

    public static function getListForAdmin(){

        $state = self::leftJoin('ai_bank', 'tmp_bank.bank_id', 'ai_bank.bank_id')
            ->leftJoin('ai_bank_acc', 'tmp_bank.acc_id', 'ai_bank_acc.acc_id')
            // ->where('id', 64573)
            ->where('tmp_bank.auto_status', 0)
            // ->where('tmp_bank.state_deposit', '<=', 500)
            // ->where('tmp_bank.test', 1)
            ->whereRaw('DATE(tmp_bank.state_date) <= ?', [date('Y-m-d')]) // Check time over now
            // ->whereRaw('DATE(tmp_bank.state_date) >= ?', ['2019-12-24 19:00:00']) // Check time over now
            ->whereRaw('TIMESTAMPDIFF(MINUTE, tmp_bank.state_date, tmp_bank.created_at) BETWEEN ? AND ?', [0, 10]) // Check time different 10 minutes
            // ->where('tmp_bank.bank_id', '!=', 6)
            // ->whereRaw('DATE(tmp_bank.created_at) = ?', ['2019-11-06'])
            // ->limit(10)
            ->orderBy('tmp_bank.id', 'asc')
            ->select(
                'tmp_bank.*',
                'ai_bank.bank_code',
                'ai_bank.bank_name',
                'ai_bank_acc.bank_account',
                'ai_bank_acc.bank_auto_credit'
            )
            ->get();

        return $state->toArray();

    }

    public static function  getListForAdminTest($id = null){

        $state = self::leftJoin('ai_bank', 'tmp_bank.bank_id', 'ai_bank.bank_id')
            ->leftJoin('ai_bank_acc', 'tmp_bank.acc_id', 'ai_bank_acc.acc_id')
            ->where('state_id', $id)
            // ->where('tmp_bank.auto_status', 0)
            // ->where('tmp_bank.state_deposit', '<=', 500)
            // ->where('tmp_bank.test', 1)
            ->whereRaw('DATE(tmp_bank.state_date) <= ?', [date('Y-m-d')]) // Check time over now
            // ->whereRaw('DATE(tmp_bank.state_date) >= ?', ['2019-12-24 19:00:00']) // Check time over now
            // ->whereRaw('TIMESTAMPDIFF(MINUTE, tmp_bank.state_date, tmp_bank.created_at) BETWEEN ? AND ?', [0, 10]) // Check time different 10 minutes
            // ->where('tmp_bank.bank_id', 3)
            // ->whereRaw('DATE(tmp_bank.created_at) = ?', ['2019-11-06'])
            // ->limit(10)
            ->orderBy('tmp_bank.id', 'asc')
            ->select(
                'tmp_bank.*',
                'ai_bank.bank_code',
                'ai_bank.bank_name',
                'ai_bank_acc.bank_account',
                'ai_bank_acc.bank_auto_credit'
            )
            ->get();

        return $state->toArray();

    }
}
