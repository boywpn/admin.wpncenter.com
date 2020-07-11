<?php

namespace Modules\Leads\Http\Controllers\Settings;

use Modules\Leads\Datatables\Settings\LeadIndustryDatatable;
use Modules\Leads\Entities\LeadIndustry;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class LeadIndustryController extends ModuleCrudController
{
    protected $datatable = LeadIndustryDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = LeadIndustry::class;

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

    protected $languageFile = 'leads::leads.industry';

    protected $routes = [
        'index' => 'leads.industry.index',
        'create' => 'leads.industry.create',
        'show' => 'leads.industry.show',
        'edit' => 'leads.industry.edit',
        'store' => 'leads.industry.store',
        'destroy' => 'leads.industry.destroy',
        'update' => 'leads.industry.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
