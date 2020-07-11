<?php

namespace Modules\Tickets\Http\Controllers\Settings;

use Modules\Tickets\Datatables\Settings\TicketSeverityDatatable;
use Modules\Tickets\Entities\TicketSeverity;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class SeverityController extends ModuleCrudController
{
    protected $datatable = TicketSeverityDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = TicketSeverity::class;

    protected $moduleName = 'tickets';

    protected $permissions = [
        'browse' => 'tickets.settings',
        'create' => 'tickets.settings',
        'update' => 'tickets.settings',
        'destroy' => 'tickets.settings'
    ];


    protected $settingsBackRoute = 'tickets.tickets.index';

    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];

    protected $languageFile = 'tickets::tickets.severity';

    protected $routes = [
        'index' => 'tickets.severity.index',
        'create' => 'tickets.severity.create',
        'show' => 'tickets.severity.show',
        'edit' => 'tickets.severity.edit',
        'store' => 'tickets.severity.store',
        'destroy' => 'tickets.severity.destroy',
        'update' => 'tickets.severity.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
