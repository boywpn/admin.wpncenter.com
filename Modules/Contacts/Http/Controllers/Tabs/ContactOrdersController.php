<?php

namespace Modules\Contacts\Http\Controllers\Tabs;

use Modules\Contacts\Datatables\Tabs\ContactOrdersDatatable;
use Modules\Contacts\Entities\Contact;
use Modules\Orders\Entities\Order;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;

/**
 * Class ContactOrdersController
 * @package Modules\Contacts\Http\Controllers
 */
class ContactOrdersController extends ModuleCrudRelationController
{
    protected $datatable = ContactOrdersDatatable::class;

    protected $ownerModel = Contact::class;

    protected $relationModel = Order::class;

    protected $ownerModuleName = 'contacts';

    protected $relatedModuleName = 'orders';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'orders';

    protected $relationType = self::RT_ONE_TO_MANY;

    protected $belongsToName = 'contact';

    protected $whereCondition = 'orders.contact_id';

    protected $whereType = self::WHERE_TYPE__COLUMN;
}
