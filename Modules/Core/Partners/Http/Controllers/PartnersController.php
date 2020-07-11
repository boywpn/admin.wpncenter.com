<?php

namespace Modules\Core\Partners\Http\Controllers;

use Modules\Core\Partners\Datatables\PartnersDatatable;
use Modules\Core\Partners\Entities\Partners;
use Modules\Core\Partners\Http\Requests\PartnersRequest;
use Modules\Core\Partners\Http\Forms\PartnersForm;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;

class PartnersController extends ModuleCrudController
{
    protected $datatable = PartnersDatatable::class;
    protected $formClass = PartnersForm::class;
    protected $storeRequest = PartnersRequest::class;
    protected $updateRequest = PartnersRequest::class;
    protected $entityClass = Partners::class;

    protected $moduleName = 'CorePartners';

    protected $permissions = [
        'browse' => 'partners.browse',
        'create' => 'partners.create',
        'update' => 'partners.update',
        'destroy' => 'partners.destroy'
    ];

    protected $showFields = [

        'information' => [
            'name' => [
                'type' => 'text',
                'col-class' => 'col-lg-4'
            ],
            'code' => [
                'type' => 'text',
                'col-class' => 'col-lg-4'
            ],
            'owner_id' => [
                'type' => 'manyToOne',
                'relation' => 'owner',
                'column' => 'name',
                'col-class' => 'col-lg-4'
            ],
            'website' => [
                'type' => 'text',
                'col-class' => 'col-lg-4'
            ],
            'phone' => [
                'type' => 'text',
                'col-class' => 'col-lg-4'
            ],
            'is_active' => [
                'type' => 'boolean',
                'col-class' => 'col-lg-4 clear-both'
            ],
            'api_active' => [
                'type' => 'boolean',
                'col-class' => 'col-lg-4'
            ],
            'api_show_report' => [
                'type' => 'boolean',
                'col-class' => 'col-lg-4'
            ]
        ],
        'note' => [
            'note' => [
                'type' => 'text',
                'col-class' => 'col-lg-12'
            ],
        ],
    ];

    protected $languageFile = 'core/partners::partners';

    protected $routes = [
        'index' => 'core.partners.index',
        'create' => 'core.partners.create',
        'show' => 'core.partners.show',
        'edit' => 'core.partners.edit',
        'store' => 'core.partners.store',
        'destroy' => 'core.partners.destroy',
        'update' => 'core.partners.update',
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
