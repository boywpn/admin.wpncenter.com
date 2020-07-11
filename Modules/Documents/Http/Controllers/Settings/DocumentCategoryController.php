<?php

namespace Modules\Documents\Http\Controllers\Settings;

use Modules\Documents\Datatables\Settings\DocumentCategoryDatatable;
use Modules\Documents\Entities\DocumentCategory;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class DocumentCategoryController extends ModuleCrudController
{
    protected $datatable = DocumentCategoryDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = DocumentCategory::class;

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

    protected $languageFile = 'documents::documents.category';

    protected $routes = [
        'index' => 'documents.category.index',
        'create' => 'documents.category.create',
        'show' => 'documents.category.show',
        'edit' => 'documents.category.edit',
        'store' => 'documents.category.store',
        'destroy' => 'documents.category.destroy',
        'update' => 'documents.category.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
