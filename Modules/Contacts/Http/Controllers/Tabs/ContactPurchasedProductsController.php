<?php

namespace Modules\Contacts\Http\Controllers\Tabs;

use Modules\Campaigns\Entities\Campaign;
use Modules\Contacts\Datatables\Scope\PurchasedProductScope;
use Modules\Contacts\Datatables\Tabs\ContactCamapginsDatatable;
use Modules\Contacts\Datatables\Tabs\ContactProductsDatatable;
use Modules\Contacts\Datatables\Tabs\ContactPurchasedProductsDatatable;
use Modules\Contacts\Entities\Contact;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;
use Modules\Products\Entities\Product;

/**
 * Class ContactPurchasedProductsController - Base on invoices
 *
 * @package Modules\Contacts\Http\Controllers\Tabs
 */
class ContactPurchasedProductsController extends ModuleCrudRelationController
{
    protected $datatable = ContactPurchasedProductsDatatable::class;

    protected $ownerModel = Contact::class;

    protected $relationModel = Product::class;

    protected $ownerModuleName = 'contacts';

    protected $relatedModuleName = 'products';

    protected $scopeLinked = PurchasedProductScope::class;

    protected $modelRelationName = 'products';

    protected $whereCondition = 'invoices.id';
}
