<?php

namespace Modules\Tickets\Http\Controllers\Settings;

use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;
use Modules\Tickets\Datatables\Settings\TicketStatusDatatable;
use Modules\Tickets\Entities\TicketStatus;

class StatusController extends ModuleCrudController
{
    protected $datatable = TicketStatusDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = TicketStatus::class;

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

    protected $languageFile = 'tickets::tickets.status';

    protected $routes = [
        'index' => 'tickets.status.index',
        'create' => 'tickets.status.create',
        'show' => 'tickets.status.show',
        'edit' => 'tickets.status.edit',
        'store' => 'tickets.status.store',
        'destroy' => 'tickets.status.destroy',
        'update' => 'tickets.status.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
