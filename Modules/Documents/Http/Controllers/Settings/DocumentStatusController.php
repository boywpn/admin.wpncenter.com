<?php

namespace Modules\Documents\Http\Controllers\Settings;

use Modules\Documents\Datatables\Settings\DocumentStatusDatatable;
use Modules\Documents\Entities\DocumentStatus;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class DocumentStatusController extends ModuleCrudController
{
    protected $datatable = DocumentStatusDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = DocumentStatus::class;

    protected $settingsBackRoute = 'documents.documents.index';

    protected $moduleName = 'documents';

    protected $permissions = [
        'browse' => 'documents.settings',
        'create' => 'documents.settings',
        'update' => 'documents.settings',
        'destroy' => 'documents.settings'
    ];


    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];

    protected $languageFile = 'documents::documents.status';

    protected $routes = [
        'index' => 'documents.status.index',
        'create' => 'documents.status.create',
        'show' => 'documents.status.show',
        'edit' => 'documents.status.edit',
        'store' => 'documents.status.store',
        'destroy' => 'documents.status.destroy',
        'update' => 'documents.status.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
