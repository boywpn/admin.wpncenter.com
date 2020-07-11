<?php

namespace Modules\ContactRequests\Http\Controllers\Settings;

use Modules\ContactRequests\Datatables\Settings\ContactReasonDatatable;
use Modules\ContactRequests\Entities\ContactReason;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class ContactReasonController extends ModuleCrudController
{

    protected $datatable = ContactReasonDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = ContactReason::class;

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

    protected $languageFile = 'contactrequests::contactrequests.contactreason';

    protected $routes = [
        'index' => 'contactrequests.contactreason.index',
        'create' => 'contactrequests.contactreason.create',
        'show' => 'contactrequests.contactreason.show',
        'edit' => 'contactrequests.contactreason.edit',
        'store' => 'contactrequests.contactreason.store',
        'destroy' => 'contactrequests.contactreason.destroy',
        'update' => 'contactrequests.contactreason.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }

}
