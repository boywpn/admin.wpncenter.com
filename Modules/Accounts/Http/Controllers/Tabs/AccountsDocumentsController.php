<?php

namespace Modules\Accounts\Http\Controllers\Tabs;

use Modules\Accounts\Datatables\Tabs\AccountDocumentsDatatable;
use Modules\Accounts\Entities\Account;
use Modules\Documents\Entities\Document;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;

/**
 * Class AccountsDocumentsController
 * @package Modules\Contacts\Http\Controllers
 */
class AccountsDocumentsController extends ModuleCrudRelationController
{
    protected $datatable = AccountDocumentsDatatable::class;

    protected $ownerModel = Account::class;

    protected $relationModel = Document::class;

    protected $ownerModuleName = 'accounts';

    protected $relatedModuleName = 'documents';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'documents';

    protected $whereCondition = 'documents.id';
}
