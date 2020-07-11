<?php

namespace Modules\ContactEmails\Entities;

use Bnb\Laravel\Attachments\HasAttachment;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Modules\ContactEmails\Service\ContactEmailService;
use Modules\Contacts\Entities\Contact;
use Modules\Platform\Core\Traits\Commentable;

class ContactEmail extends Model
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
    public $table = 'contact_email';
    public $fillable = [
        'email',
        'is_default',
        'is_active',
        'is_marketing',
        'contact_id',
        'company_id',
        'notes',
    ];
    protected $mustBeApproved = false;

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    public static function boot()
    {
        parent::boot();

        static::saved(function(ContactEmail $contactEmail){

            $contactEmailService = App::make(ContactEmailService::class);

            $contactEmailService->manageContactMultiEmail($contactEmail);

        });
    }


    /**
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }


}
