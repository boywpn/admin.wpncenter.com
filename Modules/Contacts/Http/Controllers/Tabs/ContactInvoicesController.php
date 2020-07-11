<?php

namespace Modules\Contacts\Http\Controllers\Tabs;

use Modules\Contacts\Datatables\Tabs\ContactInvoicesDatatable;
use Modules\Contacts\Datatables\Tabs\ContactOrdersDatatable;
use Modules\Contacts\Entities\Contact;
use Modules\Invoices\Entities\Invoice;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;

/**
 * Class ContactInvoicesController
 * @package Modules\Contacts\Http\Controllers
 */
class ContactInvoicesController extends ModuleCrudRelationController
{
    protected $datatable = ContactInvoicesDatatable::class;

    protected $ownerModel = Contact::class;

    protected $relationModel = Invoice::class;

    protected $ownerModuleName = 'contacts';

    protected $relatedModuleName = 'invoices';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'invoices';

    protected $relationType = self::RT_ONE_TO_MANY;

    protected $belongsToName = 'contact';

    protected $whereCondition = 'invoices.contact_id';

    protected $whereType = self::WHERE_TYPE__COLUMN;
}
