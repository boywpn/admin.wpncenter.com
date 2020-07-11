<?php

namespace Modules\Contacts\Http\Controllers\Tabs;

use Modules\Campaigns\Entities\Campaign;
use Modules\Contacts\Datatables\Tabs\ContactCamapginsDatatable;
use Modules\Contacts\Datatables\Tabs\ContactDocumentsDatatable;
use Modules\Contacts\Entities\Contact;
use Modules\Documents\Entities\Document;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;

/**
 * Class ContactDocumentsController
 * @package Modules\Contacts\Http\Controllers
 */
class ContactDocumentsController extends ModuleCrudRelationController
{
    protected $datatable = ContactDocumentsDatatable::class;

    protected $ownerModel = Contact::class;

    protected $relationModel = Document::class;

    protected $ownerModuleName = 'contacts';

    protected $relatedModuleName = 'documents';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'documents';

    protected $whereCondition = 'documents.id';
}
