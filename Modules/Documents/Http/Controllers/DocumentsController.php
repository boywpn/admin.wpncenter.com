<?php

namespace Modules\Documents\Http\Controllers;

use Modules\Documents\Datatables\DocumentDatatable;
use Modules\Documents\Entities\Document;
use Modules\Documents\Http\Forms\DocumentForm;
use Modules\Documents\Http\Requests\DocumentRequest;
use Modules\Documents\Repositories\DocumentRepository;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;

class DocumentsController extends ModuleCrudController
{
    protected $datatable = DocumentDatatable::class;
    protected $formClass = DocumentForm::class;
    protected $storeRequest = DocumentRequest::class;
    protected $updateRequest = DocumentRequest::class;
    protected $entityClass = Document::class;

    protected $moduleName = Document::MODULE_NAME;

    protected $permissions = [
        'browse' => 'documents.browse',
        'create' => 'documents.create',
        'update' => 'documents.update',
        'destroy' => 'documents.destroy'
    ];

    protected $moduleSettingsLinks = [
        ['route' => 'documents.type.index', 'label' => 'settings.type'],
        ['route' => 'documents.status.index', 'label' => 'settings.status'],
        ['route' => 'documents.category.index', 'label' => 'settings.category'],
    ];

    protected $settingsPermission = 'documents.settings';

    protected $showFields = [
        'details' => [
            'title' => ['type' => 'text', 'col-class' => 'col-lg-12'],
            'document_status_id' => ['type' => 'manyToOne', 'relation' => 'documentStatus', 'column' => 'name', 'col-class' => 'col-lg-4'],
            'document_type_id' => ['type' => 'manyToOne', 'relation' => 'documentType', 'column' => 'name', 'col-class' => 'col-lg-4'],
            'document_category_id' => ['type' => 'manyToOne', 'relation' => 'documentCategory', 'column' => 'name', 'col-class' => 'col-lg-4'],
            'owned_by' => ['type' => 'assigned_to'],
        ],
        'notes' => [
            'notes' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];

    protected $languageFile = 'documents::documents';

    protected $routes = [
        'index' => 'documents.documents.index',
        'create' => 'documents.documents.create',
        'show' => 'documents.documents.show',
        'edit' => 'documents.documents.edit',
        'store' => 'documents.documents.store',
        'destroy' => 'documents.documents.destroy',
        'update' => 'documents.documents.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
