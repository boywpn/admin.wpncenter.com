<?php

namespace Modules\LeadEmails\Entities;

use Bnb\Laravel\Attachments\HasAttachment;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Modules\LeadEmails\Service\LeadEmailService;
use Modules\Leads\Entities\Lead;
use Modules\Platform\Core\Traits\Commentable;

class LeadEmail extends Model
{

    use SoftDeletes, BelongsToTenants, Commentable, HasAttachment;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
    public $table = 'lead_email';
    public $fillable = [
        'email',
        'is_default',
        'is_active',
        'is_marketing',
        'lead_id',
        'company_id',
        'notes',
    ];
    protected $mustBeApproved = false;

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    public static function boot()
    {
        parent::boot();

        static::saved(function(LeadEmail $leadEmail){

            $leadEmailService = App::make(LeadEmailService::class);

            $leadEmailService->manageLeadMultiEmail($leadEmail);

        });
    }


    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }


}
