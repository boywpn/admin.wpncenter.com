<?php

namespace Modules\Core\BanksPartners\Entities;

use GeneaLabs\LaravelModelCaching\CachedModel;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Banks\Entities\Banks;
use Modules\Core\Partners\Entities\Partners;
use Modules\Member\Members\Entities\MembersStatus;
use Modules\Platform\Core\Traits\Commentable;
use Spatie\Activitylog\Traits\LogsActivity;

class BanksPartners extends Model
{
    use SoftDeletes, LogsActivity, Commentable, BelongsToTenants;

    const COLORS = [
        0 => 'bg-red',
        1 => 'bg-green'
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    const FORM_REMOVE_CREATE = [
    ];
    const FORM_REMOVE_EDIT = [
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    protected static $logAttributes = [
        'name',
        'banksPartner.name',
        'banksBank.name',
        'banksMembersStatus.name',
        'is_active',
        'notes'
    ];

    public $table = 'core_banks_partners';

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function banksPartner()
    {
        return $this->belongsTo(Partners::class,'partner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function banksBank()
    {
        return $this->belongsTo(Banks::class,'bank_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function banksMembersStatus()
    {
        return $this->belongsTo(MembersStatus::class,'member_status_id');
    }

    public static function getBanksPartner($partner_id, $member_status_id){

        $data = BanksPartners::where('core_banks_partners.is_active', 1)
            ->whereNull('core_banks_partners.deleted_at')
            ->where('core_banks_partners.partner_id', $partner_id)
            ->where('core_banks_partners.member_status_id', $member_status_id)
            ->join('core_banks', function ($join) {
                $join->on('core_banks_partners.bank_id', '=', 'core_banks.id')
                    ->whereNull('core_banks.deleted_at');
            })
            ->join('banks', 'core_banks.bank_id', '=', 'banks.id')
            ->select(
                'core_banks_partners.id as bank_partner_id',
                'banks.id as bank_id',
                'banks.name as bank_name',
                'banks.code as bank_code',
                'core_banks.id as core_bank_id',
                'core_banks.account as bank_account',
                'core_banks.number as bank_number'
            )
            ->orderBy('banks.sort', 'asc')
            ->orderBy('core_banks.account', 'asc')
            ->get()->toArray();

        return $data;

    }

}
