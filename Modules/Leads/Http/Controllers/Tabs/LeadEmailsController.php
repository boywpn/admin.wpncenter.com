<?php

namespace Modules\Leads\Http\Controllers\Tabs;

use Modules\ContactEmails\Entities\ContactEmail;
use Modules\Contacts\Datatables\Tabs\ContactEmailDatatable;
use Modules\Contacts\Entities\Contact;
use Modules\LeadEmails\Entities\LeadEmail;
use Modules\Leads\Datatables\Tabs\LeadEmailDatatable;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;

/**
 * Class LeadEmailsController
 * @package Modules\Leads\Http\Controllers\Tabs
 */
class LeadEmailsController extends ModuleCrudRelationController
{
    protected $datatable = LeadEmailDatatable::class;

    protected $ownerModel = Contact::class;

    protected $relationModel = LeadEmail::class;

    protected $ownerModuleName = 'leads';

    protected $relatedModuleName = 'leademails';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'leadEmails';

    protected $relationType = self::RT_ONE_TO_MANY;

    protected $belongsToName = 'lead';

    protected $whereCondition = 'lead_email.lead_id';

    protected $whereType = self::WHERE_TYPE__COLUMN;
}
