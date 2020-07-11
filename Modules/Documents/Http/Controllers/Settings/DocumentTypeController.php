<?php

namespace Modules\Documents\Http\Controllers\Settings;

use Modules\Documents\Datatables\Settings\DocumentTypeDatatable;
use Modules\Documents\Entities\DocumentType;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class DocumentTypeController extends ModuleCrudController
{
    protected $datatable = DocumentTypeDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = DocumentType::class;

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

    protected $languageFile = 'documents::documents.type';

    protected $routes = [
        'index' => 'documents.type.index',
        'create' => 'documents.type.create',
        'show' => 'documents.type.show',
        'edit' => 'documents.type.edit',
        'store' => 'documents.type.store',
        'destroy' => 'documents.type.destroy',
        'update' => 'documents.type.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
