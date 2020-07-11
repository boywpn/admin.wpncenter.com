<?php

namespace Modules\Contacts\Http\Controllers\Settings;

use Modules\Contacts\Datatables\Settings\ContactStatusDatatable;
use Modules\Contacts\Entities\ContactStatus;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Forms\NameIconDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class StatusController extends ModuleCrudController
{
    protected $datatable = ContactStatusDatatable::class;
    protected $formClass = NameIconDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = ContactStatus::class;

    protected $disableWidgets = true;

    protected $moduleName = 'contacts';

    protected $permissions = [
        'browse' => 'contacts.settings',
        'create' => 'contacts.settings',
        'update' => 'contacts.settings',
        'destroy' => 'contacts.settings'
    ];

    protected $settingsBackRoute = 'contacts.contacts.index';

    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text', 'col-class' => 'col-lg-12'],
            'icon' => ['type' => 'text', 'col-class' => 'col-lg-12'],
            'color' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];

    protected $languageFile = 'contacts::contacts.status';

    protected $routes = [
        'index' => 'contacts.status.index',
        'create' => 'contacts.status.create',
        'show' => 'contacts.status.show',
        'edit' => 'contacts.status.edit',
        'store' => 'contacts.status.store',
        'destroy' => 'contacts.status.destroy',
        'update' => 'contacts.status.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
