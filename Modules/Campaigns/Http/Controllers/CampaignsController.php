<?php

namespace Modules\Campaigns\Http\Controllers;

use Modules\Campaigns\Datatables\CampaignDatatable;
use Modules\Campaigns\Datatables\Tabs\CampaignsAccountsDatatable;
use Modules\Campaigns\Datatables\Tabs\CampaignsContactsDatatable;
use Modules\Campaigns\Datatables\Tabs\CampaignsDealsDatatable;
use Modules\Campaigns\Datatables\Tabs\CampaignsLeadDatatable;
use Modules\Campaigns\Entities\Campaign;
use Modules\Campaigns\Http\Forms\CampaignForm;
use Modules\Campaigns\Http\Requests\CampaignsRequest;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;

class CampaignsController extends ModuleCrudController
{
    protected $datatable = CampaignDatatable::class;
    protected $formClass = CampaignForm::class;
    protected $storeRequest = CampaignsRequest::class;
    protected $updateRequest = CampaignsRequest::class;
    protected $entityClass = Campaign::class;

    protected $moduleName = 'campaigns';

    protected $permissions = [
        'browse' => 'campaigns.browse',
        'create' => 'campaigns.create',
        'update' => 'campaigns.update',
        'destroy' => 'campaigns.destroy'
    ];

    protected $moduleSettingsLinks = [

        ['route' => 'campaigns.status.index', 'label' => 'settings.status'],
        ['route' => 'campaigns.type.index', 'label' => 'settings.type'],


    ];

    protected $settingsPermission = 'campaigns.settings';

    protected $showFields = [

        'information' => [

            'name' => [
                'type' => 'text',
            ],


            'owned_by' => [
                'type' => 'assigned_to',
            ],


            'product' => [
                'type' => 'text',
            ],


            'target_audience' => [
                'type' => 'text',
            ],


            'expected_close_date' => [
                'type' => 'date',
            ],


            'sponsor' => [
                'type' => 'text',
            ],


            'target_size' => [
                'type' => 'text',
            ],


            'campaign_status_id' => [
                'type' => 'manyToOne',
                'relation' => 'campaignStatus',
                'column' => 'name'
            ],


            'campaign_type_id' => [
                'type' => 'manyToOne',
                'relation' => 'campaignType',
                'column' => 'name'
            ],

        ],


        'expectations_and_actuals' => [

            'budget_cost' => [
                'type' => 'text',
            ],


            'actual_budget' => [
                'type' => 'text',
            ],


            'expected_response' => [
                'type' => 'text',
            ],


            'expected_revenue' => [
                'type' => 'text',
            ],


            'expected_sales_count' => [
                'type' => 'text',
            ],


            'actual_sales_count' => [
                'type' => 'text',
            ],


            'expected_response_count' => [
                'type' => 'text',
            ],


            'actual_response_count' => [
                'type' => 'text',
            ],


            'expected_roi' => [
                'type' => 'text',
            ],


            'actual_roi' => [
                'type' => 'text',
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

        'leads' => [
            'icon' => 'search',
            'permissions' => [
                'browse' => 'leads.browse',
                'update' => 'leads.update',
                'create' => 'leads.create'
            ],
            'datatable' => [
                'datatable' => CampaignsLeadDatatable::class
            ],
            'route' => [
                'linked' => 'campaigns.leads.linked',
                'create' => 'leads.leads.create',
                'select' => 'campaigns.leads.selection',
                'bind_selected' => 'campaigns.leads.link'
            ],
            'create' => [
                'allow' => true,
                'modal_title' => 'leads::leads.create_new',
                'post_create_bind' => [
                    'relationType' => 'manyToMany',
                    'relatedField' => 'campaigns',
                ]
            ],

            'select' => [
                'allow' => true,
                'modal_title' => 'leads::leads.module'
            ],

        ],

        'contacts' => [
            'icon' => 'contacts',
            'permissions' => [
                'browse' => 'contacts.browse',
                'update' => 'contacts.update',
                'create' => 'contacts.create'
            ],
            'datatable' => [
                'datatable' => CampaignsContactsDatatable::class
            ],
            'route' => [
                'linked' => 'campaigns.contacts.linked',
                'create' => 'contacts.contacts.create',
                'select' => 'campaigns.contacts.selection',
                'bind_selected' => 'campaigns.contacts.link'
            ],
            'create' => [
                'allow' => true,
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

        'deals' => [
            'icon' => 'monetization_on',
            'permissions' => [
                'browse' => 'deals.browse',
                'update' => 'deals.update',
                'create' => 'deals.create'
            ],
            'datatable' => [
                'datatable' => CampaignsDealsDatatable::class
            ],
            'route' => [
                'linked' => 'campaigns.deals.linked',
                'create' => 'deals.deals.create',
                'select' => 'campaigns.deals.selection',
                'bind_selected' => 'campaigns.deals.link'
            ],
            'create' => [
                'allow' => true,
                'modal_title' => 'deals::deals.create_new',
                'post_create_bind' => [
                    'relationType' => 'manyToMany',
                    'relatedField' => 'campaigns',
                ]
            ],
            'select' => [
                'allow' => false,
                'modal_title' => 'deals::deals.module'
            ],
        ],

        'accounts' => [
            'icon' => 'business',
            'permissions' => [
                'browse' => 'accounts.browse',
                'update' => 'accounts.update',
                'create' => 'accounts.create'
            ],
            'datatable' => [
                'datatable' => CampaignsAccountsDatatable::class
            ],
            'route' => [
                'linked' => 'campaigns.accounts.linked',
                'create' => 'accounts.accounts.create',
                'select' => 'campaigns.accounts.selection',
                'bind_selected' => 'campaigns.accounts.link'
            ],
            'create' => [
                'allow' => true,
                'modal_title' => 'accounts::accounts.create_new',
                'post_create_bind' => [
                    'relationType' => 'manyToMany',
                    'relatedField' => 'campaigns',
                ]
            ],
            'select' => [
                'allow' => true,
                'modal_title' => 'accounts::accounts.module'
            ],
        ]
    ];


    protected $languageFile = 'campaigns::campaigns';

    protected $routes = [
        'index' => 'campaigns.campaigns.index',
        'create' => 'campaigns.campaigns.create',
        'show' => 'campaigns.campaigns.show',
        'edit' => 'campaigns.campaigns.edit',
        'store' => 'campaigns.campaigns.store',
        'destroy' => 'campaigns.campaigns.destroy',
        'update' => 'campaigns.campaigns.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
