<?php

namespace Modules\ContactRequests\Http\Controllers\Settings;

use Modules\ContactRequests\Datatables\Settings\PreferredContactMethodDatatable;
use Modules\ContactRequests\Entities\PreferredContactMethod;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class PreferredContactMethodController extends ModuleCrudController
{

    protected $datatable = PreferredContactMethodDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = PreferredContactMethod::class;

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

    protected $languageFile = 'contactrequests::contactrequests.preferredcontactmethod';

    protected $routes = [
        'index' => 'contactrequests.preferredcontactmethod.index',
        'create' => 'contactrequests.preferredcontactmethod.create',
        'show' => 'contactrequests.preferredcontactmethod.show',
        'edit' => 'contactrequests.preferredcontactmethod.edit',
        'store' => 'contactrequests.preferredcontactmethod.store',
        'destroy' => 'contactrequests.preferredcontactmethod.destroy',
        'update' => 'contactrequests.preferredcontactmethod.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }

}
