<?php

namespace Modules\Deals\Http\Controllers;

use Modules\Deals\Datatables\DealDatatable;
use Modules\Deals\Datatables\Tabs\DealsContactsDatatable;
use Modules\Deals\Entities\Deal;
use Modules\Deals\Http\Forms\DealForm;
use Modules\Deals\Http\Requests\DealsRequest;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;

class DealsController extends ModuleCrudController
{
    protected $datatable = DealDatatable::class;
    protected $formClass = DealForm::class;
    protected $storeRequest = DealsRequest::class;
    protected $updateRequest = DealsRequest::class;
    protected $entityClass = Deal::class;

    protected $moduleName = 'deals';

    protected $permissions = [
        'browse' => 'deals.browse',
        'create' => 'deals.create',
        'update' => 'deals.update',
        'destroy' => 'deals.destroy'
    ];

    protected $moduleSettingsLinks = [

        ['route' => 'deals.stage.index', 'label' => 'settings.stage'],
        ['route' => 'deals.businesstype.index', 'label' => 'settings.businesstype'],


    ];

    protected $settingsPermission = 'deals.settings';

    protected $relationTabs = [

        'contacts' => [
            'icon' => 'contacts',
            'permissions' => [
                'browse' => 'contacts.browse',
                'update' => 'contacts.update',
                'create' => 'contacts.create'
            ],
            'datatable' => [
                'datatable' => DealsContactsDatatable::class
            ],
            'route' => [
                'linked' => 'deals.contacts.linked',
                'create' => 'contacts.contacts.create',
                'select' => 'deals.contacts.selection',
                'bind_selected' => 'deals.contacts.link'
            ],
            'create' => [
                'allow' => false,
                'modal_title' => 'contacts::contacts.create_new',
                'post_create_bind' => [
                    'relationType' => 'manyToMany',
                    'relatedField' => 'campaigns',
                ]
            ],

            'select' => [
                'allow' => true,
                'modal_title' => 'contacts::contacts.module'
            ],

        ],
    ];


    protected $showFields = [

        'information' => [

            'name' => [
                'type' => 'text',
            ],


            'owned_by' => [
                'type' => 'assigned_to',
            ],


            'amount' => [
                'type' => 'text',
            ],


            'closing_date' => [
                'type' => 'date',
            ],


            'probability' => [
                'type' => 'text',
            ],


            'expected_revenue' => [
                'type' => 'text',
            ],


            'next_step' => [
                'type' => 'text',
            ],


            'deal_stage_id' => [
                'type' => 'manyToOne',
                'relation' => 'dealStage',
                'column' => 'name'
            ],

            'deal_business_type_id' => [
                'type' => 'manyToOne',
                'relation' => 'dealBusinessType',
                'column' => 'name'
            ],

            'account_id' => [
                'type' => 'manyToOne',
                'relation' => 'account',
                'column' => 'name',
                'dont_translate' => true
            ],

        ],


        'notes' => [

            'notes' => [
                'type' => 'text',
                'col-class' => 'col-lg-12'
            ],

        ],


    ];

    protected $languageFile = 'deals::deals';

    protected $routes = [
        'index' => 'deals.deals.index',
        'create' => 'deals.deals.create',
        'show' => 'deals.deals.show',
        'edit' => 'deals.deals.edit',
        'store' => 'deals.deals.store',
        'destroy' => 'deals.deals.destroy',
        'update' => 'deals.deals.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
