<?php

namespace Modules\Contacts\Http\Controllers\Tabs;

use Modules\ContactEmails\Entities\ContactEmail;
use Modules\Contacts\Datatables\Tabs\ContactEmailDatatable;
use Modules\Contacts\Entities\Contact;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;

/**
 * Class ContactCallsController
 * @package Modules\Contacts\Http\Controllers\Tabs
 */
class ContactEmailsController extends ModuleCrudRelationController
{
    protected $datatable = ContactEmailDatatable::class;

    protected $ownerModel = Contact::class;

    protected $relationModel = ContactEmail::class;

    protected $ownerModuleName = 'contacts';

    protected $relatedModuleName = 'contactemails';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'contactEmails';

    protected $relationType = self::RT_ONE_TO_MANY;

    protected $belongsToName = 'contact';

    protected $whereCondition = 'contact_email.contact_id';

    protected $whereType = self::WHERE_TYPE__COLUMN;
}
