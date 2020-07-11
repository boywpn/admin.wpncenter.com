<?php

namespace Modules\Leads\Http\Controllers\Settings;

use Modules\Leads\Datatables\Settings\LeadStatusDatatable;
use Modules\Leads\Entities\LeadStatus;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Forms\NameIconDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class LeadStatusController extends ModuleCrudController
{
    protected $datatable = LeadStatusDatatable::class;
    protected $formClass = NameIconDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = LeadStatus::class;

    protected $disableWidgets = true;

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

    protected $languageFile = 'leads::leads.status';

    protected $routes = [
        'index' => 'leads.status.index',
        'create' => 'leads.status.create',
        'show' => 'leads.status.show',
        'edit' => 'leads.status.edit',
        'store' => 'leads.status.store',
        'destroy' => 'leads.status.destroy',
        'update' => 'leads.status.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
