<?php

namespace Modules\Accounts\Http\Controllers\Tabs;

use Modules\Accounts\Datatables\Scope\ContactRelationScope;
use Modules\Accounts\Datatables\Tabs\AccountsContactsDatatable;
use Modules\Accounts\Entities\Account;
use Modules\Contacts\Entities\Contact;
use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;

class AccountsContactsController extends ModuleCrudRelationController
{
    protected $datatable = AccountsContactsDatatable::class;

    protected $ownerModel = Account::class;

    protected $relationModel = Contact::class;

    protected $ownerModuleName = 'accounts';

    protected $relatedModuleName = 'contacts';

    protected $scopeLinked = BasicRelationScope::class;

    protected $modelRelationName = 'contacts';

    protected $relationType = self::RT_ONE_TO_MANY;

    protected $belongsToName = 'account';

    protected $whereCondition = 'contacts.id';
}
