<?php

namespace Modules\Contacts\Http\Controllers\Tabs;

use Modules\Calls\Entities\Call;
use Modules\Contacts\Datatables\Tabs\ContactCallsDatatable;
use Modules\Contacts\Entities\Contact;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;

/**
 * Class ContactCallsController
 * @package Modules\Contacts\Http\Controllers\Tabs
 */
class ContactCallsController extends ModuleCrudRelationController
{
    protected $datatable = ContactCallsDatatable::class;

    protected $ownerModel = Contact::class;

    protected $relationModel = Call::class;

    protected $ownerModuleName = 'contacts';

    protected $relatedModuleName = 'calls';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'calls';

    protected $relationType = self::RT_ONE_TO_MANY;

    protected $belongsToName = 'contact';

    protected $whereCondition = 'calls.contact_id';

    protected $whereType = self::WHERE_TYPE__COLUMN;
}
