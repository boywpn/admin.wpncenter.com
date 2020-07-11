<?php

namespace Modules\Contacts\Http\Controllers\Settings;

use Modules\Contacts\Datatables\Settings\ContactSourceDatatable;
use Modules\Contacts\Entities\ContactSource;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Forms\NameIconDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class SourceController extends ModuleCrudController
{
    protected $datatable = ContactSourceDatatable::class;
    protected $formClass = NameIconDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = ContactSource::class;

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

    protected $languageFile = 'contacts::contacts.source';

    protected $routes = [
        'index' => 'contacts.source.index',
        'create' => 'contacts.source.create',
        'show' => 'contacts.source.show',
        'edit' => 'contacts.source.edit',
        'store' => 'contacts.source.store',
        'destroy' => 'contacts.source.destroy',
        'update' => 'contacts.source.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
