<?php

namespace Modules\Contacts\Http\Controllers\Tabs;

use Modules\Campaigns\Entities\Campaign;
use Modules\Contacts\Datatables\Tabs\ContactCamapginsDatatable;
use Modules\Contacts\Entities\Contact;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;

/**
 * Class ContactCampaignsController
 * @package Modules\Contacts\Http\Controllers
 */
class ContactCampaignsController extends ModuleCrudRelationController
{
    protected $datatable = ContactCamapginsDatatable::class;

    protected $ownerModel = Contact::class;

    protected $relationModel = Campaign::class;

    protected $ownerModuleName = 'contacts';

    protected $relatedModuleName = 'campaigns';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'campaigns';

    protected $whereCondition = 'campaigns.id';
}
