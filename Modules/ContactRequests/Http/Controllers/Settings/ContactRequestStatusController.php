<?php

namespace Modules\ContactRequests\Http\Controllers\Settings;

use Modules\ContactRequests\Datatables\Settings\ContactRequestStatusDatatable;
use Modules\ContactRequests\Entities\ContactRequestStatus;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class ContactRequestStatusController extends ModuleCrudController
{

    protected $datatable = ContactRequestStatusDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = ContactRequestStatus::class;

    protected $moduleName = 'contactrequests';

    protected $permissions = [
        'browse' => 'contactrequests.settings',
        'create' => 'contactrequests.settings',
        'update' => 'contactrequests.settings',
        'destroy' => 'contactrequests.settings'
    ];

    protected $settingsBackRoute = 'contactrequests.contactrequests.index';

    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];

    protected $languageFile = 'contactrequests::contactrequests.contactrequeststatus';

    protected $routes = [
        'index' => 'contactrequests.contactrequeststatus.index',
        'create' => 'contactrequests.contactrequeststatus.create',
        'show' => 'contactrequests.contactrequeststatus.show',
        'edit' => 'contactrequests.contactrequeststatus.edit',
        'store' => 'contactrequests.contactrequeststatus.store',
        'destroy' => 'contactrequests.contactrequeststatus.destroy',
        'update' => 'contactrequests.contactrequeststatus.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }

}
