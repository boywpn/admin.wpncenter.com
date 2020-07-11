<?php

namespace Modules\Contacts\Http\Controllers\Tabs;

use Modules\Campaigns\Entities\Campaign;
use Modules\Contacts\Datatables\Tabs\ContactCamapginsDatatable;
use Modules\Contacts\Datatables\Tabs\ContactProductsDatatable;
use Modules\Contacts\Entities\Contact;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;
use Modules\Products\Entities\Product;

/**
 * Class ContactProductsController
 * @package Modules\Contacts\Http\Controllers
 */
class ContactProductsController extends ModuleCrudRelationController
{
    protected $datatable = ContactProductsDatatable::class;

    protected $ownerModel = Contact::class;

    protected $relationModel = Product::class;

    protected $ownerModuleName = 'contacts';

    protected $relatedModuleName = 'products';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'products';

    protected $whereCondition = 'products.id';
}
