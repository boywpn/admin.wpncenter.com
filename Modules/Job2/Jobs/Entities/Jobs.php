<?php

namespace Modules\Job\Jobs\Entities;

use Bnb\Laravel\Attachments\HasAttachment;
use GeneaLabs\LaravelModelCaching\CachedModel;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Banks\Entities\Banks;
use Modules\Core\BanksPartners\Entities\BanksPartners;
use Modules\Core\Partners\Entities\Partners;
use Modules\Core\Username\Entities\Username;
use Modules\Member\Members\Entities\Members;
use Modules\Member\Members\Entities\MembersBanks;
use Modules\Platform\Core\Traits\Commentable;
use Modules\Platform\User\Entities\User;
use Spatie\Activitylog\Traits\LogsActivity;

class Jobs extends Model
{
    use SoftDeletes, LogsActivity, Commentable, BelongsToTenants, HasAttachment;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    protected static $logAttributes = [
        'jobsType.name',
        'jobsStatus.name',

        'order_code',
        'order_api_id',
        'ref',

        'jobsMember.name',
        'jobsUsername.username',

        'topupFromBank.bank_number',
        'topupToBank.number',
        'topup_pay_at',
        'topup_slip',

        'withdrawFromBank.number',
        'withdrawToBank.bank_number',
        'withdraw_slip',
        'withdraw_at',

        'transfer_type',

        'amount',
        'promotion_amount',
        'total_amount',

        'jobsLocked.name',
        'locked_at',
        'locked_at',
        'locked_by_name',
        'jobsBanker.name',
        'banker_at',
        'jobsCanceled.name',
        'canceled_at',
        'canceled_notes',
        'jobsCompleted.name',
        'completed_at',
        'ip_address',
        'statement_id',
    ];

    public $table = 'job_jobs';

    public $fillable = [
        'topup_slip',
    ];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jobsMember()
    {
        return $this->belongsTo(Members::class, 'member_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jobsUsername()
    {
        return $this->belongsTo(Username::class, 'username_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function topupFromBank()
    {
        return $this->belongsTo(MembersBanks::class, 'topup_from_bank');
    }
    public function topupToBank()
    {
        return $this->belongsTo(BanksPartners::class, 'topup_to_bank');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function withdrawFromBank()
    {
        return $this->belongsTo(Banks::class, 'withdraw_from_bank');
    }
    public function withdrawToBank()
    {
        return $this->belongsTo(MembersBanks::class, 'withdraw_to_bank');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
//    public function transferToUsername()
//    {
//        return $this->belongsTo(Username::class, 'transfer_to_username');
//    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jobsLocked()
    {
        return $this->belongsTo(User::class, 'locked_by');
    }
    public function jobsBanker()
    {
        return $this->belongsTo(User::class, 'banker_by');
    }
    public function jobsCanceled()
    {
        return $this->belongsTo(User::class, 'canceled_by');
    }
    public function jobsCompleted()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jobsStatus()
    {
        return $this->belongsTo(JobsStatus::class, 'status_id');
    }
    public function jobsType()
    {
        return $this->belongsTo(JobsType::class, 'type_id');
    }

    public static function getJobs($type, $status = null){

        $jobs = Jobs::where('job_jobs.transfer_type', $type)
            ->where('job_jobs.status_id', $status)
            ->with(['jobsMember' => function($query){
                $query->select('id', 'agent_id', 'username', 'name', 'phone');
            }, 'jobsMember.membersAgent' => function($query){
                $query->select('id', 'partner_id', 'name', 'ref');
            }, 'jobsMember.membersAgent.agentsPartner' => function($query){
                $query->select('id', 'name');
            }])
            ->with(['jobsUsername' => function($query){
                $query->select('id','board_id','username');
            }])
            ->orderBy('job_jobs.created_at', 'desc')
            ->select('*')
            ->get();

        return $jobs->toArray();

    }

    public static function getJobByID($id){

        $jobs = Jobs::where('id', $id)
            ->with(['jobsMember' => function($query){
                $query->select('*');
            }, 'jobsMember.membersAgent' => function($query){
                $query->select('*');
            }, 'jobsMember.membersAgent.agentsPartner' => function($query){
                $query->select('*');
            }, 'jobsMember.banksMember' => function($query){
                $query->select('*');
            }, 'jobsMember.banksMember.banksBank' => function($query){
                $query->select('*');
            }])

            ->with(['jobsUsername' => function($query){
                $query->select('*');
            }, 'jobsUsername.usernameBoard' => function($query){
                $query->select('*');
            }, 'jobsUsername.usernameBoard.boardsGame' => function($query){
                $query->select('*');
            }])

            ->with(['topupFromBank' => function($query){
                $query->select('*');
            }])

            ->with(['topupToBank' => function($query){
                $query->select('*');
            }, 'topupToBank.banksBank' => function($query){
                $query->select('*');
            }, 'topupToBank.banksBank.banks' => function($query){
                $query->select('*');
            }])

            ->with(['withdrawFromBank' => function($query){
                $query->select('*');
            }])

            ->with(['withdrawToBank' => function($query){
                $query->select('*');
            }])

            ->orderBy('job_jobs.created_at', 'desc')
            ->select('*')
            ->first();

        return $jobs->toArray();

    }

    public static function lockJobByID($id){

        $jobs = Jobs::where('id', $id)
            ->select('*')
            ->first();

        return $jobs->toArray();

    }


}
