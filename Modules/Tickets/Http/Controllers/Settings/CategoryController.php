<?php

namespace Modules\Tickets\Http\Controllers\Settings;

use Modules\Tickets\Datatables\Settings\TicketCategoryDatatable;
use Modules\Tickets\Entities\TicketCategory;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class CategoryController extends ModuleCrudController
{
    protected $datatable = TicketCategoryDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = TicketCategory::class;

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

    protected $languageFile = 'tickets::tickets.category';

    protected $routes = [
        'index' => 'tickets.category.index',
        'create' => 'tickets.category.create',
        'show' => 'tickets.category.show',
        'edit' => 'tickets.category.edit',
        'store' => 'tickets.category.store',
        'destroy' => 'tickets.category.destroy',
        'update' => 'tickets.category.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
