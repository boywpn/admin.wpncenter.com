<?php

namespace Modules\Leads\Http\Controllers\Settings;

use Modules\Leads\Datatables\Settings\LeadSourceDatatable;
use Modules\Leads\Entities\LeadSource;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class LeadSourceController extends ModuleCrudController
{
    protected $datatable = LeadSourceDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = LeadSource::class;

    protected $settingsBackRoute = 'leads.leads.index';

    protected $moduleName = 'leads';

    protected $permissions = [
        'browse' => 'leads.settings',
        'create' => 'leads.settings',
        'update' => 'leads.settings',
        'destroy' => 'leads.settings'
    ];

    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];

    protected $languageFile = 'leads::leads.source';

    protected $routes = [
        'index' => 'leads.source.index',
        'create' => 'leads.source.create',
        'show' => 'leads.source.show',
        'edit' => 'leads.source.edit',
        'store' => 'leads.source.store',
        'destroy' => 'leads.source.destroy',
        'update' => 'leads.source.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
