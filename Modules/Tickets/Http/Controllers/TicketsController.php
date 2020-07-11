<?php

namespace Modules\Tickets\Http\Controllers;

use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Tickets\Datatables\Tabs\TicketsTicketsDatatable;
use Modules\Tickets\Datatables\TicketDatatable;
use Modules\Tickets\Entities\Ticket;
use Modules\Tickets\Http\Forms\TicketForm;
use Modules\Tickets\Http\Requests\TicketsRequest;

class TicketsController extends ModuleCrudController
{
    protected $datatable = TicketDatatable::class;
    protected $formClass = TicketForm::class;
    protected $storeRequest = TicketsRequest::class;
    protected $updateRequest = TicketsRequest::class;
    protected $entityClass = Ticket::class;

    protected $moduleName = 'tickets';


    protected $permissions = [
        'browse' => 'tickets.browse',
        'create' => 'tickets.create',
        'update' => 'tickets.update',
        'destroy' => 'tickets.destroy'
    ];

    protected $moduleSettingsLinks = [

        ['route' => 'tickets.priority.index', 'label' => 'settings.priority'],
        ['route' => 'tickets.status.index', 'label' => 'settings.status'],
        ['route' => 'tickets.severity.index', 'label' => 'settings.severity'],
        ['route' => 'tickets.category.index', 'label' => 'settings.category'],


    ];

    protected $settingsPermission = 'tickets.settings';

    protected $showFields = [

        'information' => [

            'name' => [
                'type' => 'text',
            ],


            'due_date' => [
                'type' => 'date',
            ],


            'owned_by' => [
                'type' => 'assigned_to',
            ],


            'ticket_priority_id' => [
                'type' => 'manyToOne',
                'relation' => 'ticketPriority',
                'column' => 'name'
            ],


            'ticket_status_id' => [
                'type' => 'manyToOne',
                'relation' => 'ticketStatus',
                'column' => 'name'
            ],


            'ticket_severity_id' => [
                'type' => 'manyToOne',
                'relation' => 'ticketSeverity',
                'column' => 'name',
            ],


            'ticket_category_id' => [
                'type' => 'manyToOne',
                'relation' => 'ticketCategory',
                'column' => 'name'
            ],

            'contact_id' => [
                'type' => 'manyToOne',
                'relation' => 'contact',
                'column' => 'full_name',
                'dont_translate' => true
            ],

            'account_id' => [
                'type' => 'manyToOne',
                'relation' => 'account',
                'column' => 'name',
                'dont_translate' => true
            ],

            'parent_id' => [
                'type' => 'manyToOne',
                'relation' => 'parent',
                'column' => 'name',
                'dont_translate' => true
            ],

        ],


        'description' => [

            'description' => [
                'type' => 'text',
                'col-class' => 'col-lg-12'
            ],

        ],


        'resolution' => [

            'resolution' => [
                'type' => 'text',
                'col-class' => 'col-lg-12'
            ],

        ],


        'notes' => [

            'notes' => [
                'type' => 'text',
                'col-class' => 'col-lg-12'
            ],

        ],


    ];

    protected $relationTabs = [


        'tickets' => [
            'icon' => 'report_problem',
            'permissions' => [
                'browse' => 'tickets.browse',
                'update' => 'tickets.update',
                'create' => 'tickets.create'
            ],
            'datatable' => [
                'datatable' => TicketsTicketsDatatable::class
            ],
            'route' => [
                'linked' => 'tickets.tickets.linked',
                'create' => 'tickets.tickets.create',
                'select' => 'tickets.tickets.selection',
                'bind_selected' => 'tickets.tickets.link'
            ],
            'create' => [
                'allow' => true,
                'modal_title' => 'tickets::tickets.create_new',
                'post_create_bind' => [
                    'relationType' => 'oneToMany',
                    'relatedField' => 'parent_id',

                ]
            ],

            'select' => [
                'allow' => false,
                'modal_title' => 'tickets::tickets.module'
            ],
        ],
    ];


    protected $languageFile = 'tickets::tickets';

    protected $routes = [
        'index' => 'tickets.tickets.index',
        'create' => 'tickets.tickets.create',
        'show' => 'tickets.tickets.show',
        'edit' => 'tickets.tickets.edit',
        'store' => 'tickets.tickets.store',
        'destroy' => 'tickets.tickets.destroy',
        'update' => 'tickets.tickets.update'
    ];


    public function __construct()
    {
        parent::__construct();
    }
}
