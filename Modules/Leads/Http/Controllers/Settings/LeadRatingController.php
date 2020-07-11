<?php

namespace Modules\Leads\Http\Controllers\Settings;

use Modules\Leads\Datatables\Settings\LeadRatingDatatable;
use Modules\Leads\Entities\LeadRating;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class LeadRatingController extends ModuleCrudController
{
    protected $datatable = LeadRatingDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = LeadRating::class;

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

    protected $languageFile = 'leads::leads.rating';

    protected $routes = [
        'index' => 'leads.rating.index',
        'create' => 'leads.rating.create',
        'show' => 'leads.rating.show',
        'edit' => 'leads.rating.edit',
        'store' => 'leads.rating.store',
        'destroy' => 'leads.rating.destroy',
        'update' => 'leads.rating.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
