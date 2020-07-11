<?php

namespace Modules\Accounts\Http\Controllers;

use Modules\Accounts\Datatables\AccountDatatable;
use Modules\Accounts\Datatables\Tabs\AccountAssetsDatatable;
use Modules\Accounts\Datatables\Tabs\AccountCallsDatatable;
use Modules\Accounts\Datatables\Tabs\AccountCampaignsDatatable;
use Modules\Accounts\Datatables\Tabs\AccountDealsDatatable;
use Modules\Accounts\Datatables\Tabs\AccountDocumentsDatatable;
use Modules\Accounts\Datatables\Tabs\AccountInvoicesDatatable;
use Modules\Accounts\Datatables\Tabs\AccountOrdersDatatable;
use Modules\Accounts\Datatables\Tabs\AccountQuotesDatatable;
use Modules\Accounts\Datatables\Tabs\AccountsContactsDatatable;
use Modules\Accounts\Datatables\Tabs\AccountServiceContractsDatatable;
use Modules\Accounts\Datatables\Tabs\AccountTicketsDatatable;
use Modules\Accounts\Entities\Account;
use Modules\Accounts\Http\Forms\AccountForm;
use Modules\Accounts\Http\Requests\AccountsRequest;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;

class AccountsController extends ModuleCrudController
{
    protected $datatable = AccountDatatable::class;
    protected $formClass = AccountForm::class;
    protected $storeRequest = AccountsRequest::class;
    protected $updateRequest = AccountsRequest::class;
    protected $entityClass = Account::class;

    protected $moduleName = 'accounts';

    protected $permissions = [
        'browse' => 'accounts.browse',
        'create' => 'accounts.create',
        'update' => 'accounts.update',
        'destroy' => 'accounts.destroy'
    ];

    protected $moduleSettingsLinks = [

        ['route' => 'accounts.type.index', 'label' => 'settings.type'],
        ['route' => 'accounts.rating.index', 'label' => 'settings.rating'],
        ['route' => 'accounts.industry.index', 'label' => 'settings.industry'],


    ];

    protected $settingsPermission = 'accounts.settings';

    protected $relationTabs = [

        'contacts' => [
            'icon' => 'contacts',
            'permissions' => [
                'browse' => 'contacts.browse',
                'update' => 'contacts.update',
                'create' => 'contacts.create'
            ],
            'datatable' => [
                'datatable' => AccountsContactsDatatable::class
            ],
            'route' => [
                'linked' => 'accounts.contacts.linked',
                'create' => 'contacts.contacts.create',
                'select' => 'accounts.contacts.selection',
                'bind_selected' => 'accounts.contacts.link'
            ],
            'create' => [
                'allow' => true,
                'modal_title' => 'contacts::contacts.create_new',
                'post_create_bind' => [
                    'relationType' => 'oneToMany',
                    'relatedField' => 'account_id',

                ]
            ],

            'select' => [
                'allow' => false,
                'modal_title' => 'contacts::contacts.module'
            ],
        ],
        'tickets' => [
            'icon' => 'report_problem',
            'permissions' => [
                'browse' => 'tickets.browse',
                'update' => 'tickets.update',
                'create' => 'tickets.create'
            ],
            'datatable' => [
                'datatable' => AccountTicketsDatatable::class
            ],
            'route' => [
                'linked' => 'accounts.tickets.linked',
                'create' => 'tickets.tickets.create',
                'select' => 'accounts.tickets.selection',
                'bind_selected' => 'accounts.tickets.link'
            ],
            'create' => [
                'allow' => true,
                'modal_title' => 'tickets::tickets.create_new',
                'post_create_bind' => [
                    'relationType' => 'oneToMany',
                    'relatedField' => 'account_id',

                ]
            ],

            'select' => [
                'allow' => false,
                'modal_title' => 'tickets::tickets.module'
            ],
        ],
        'calls' => [
            'icon' => 'phone',
            'permissions' => [
                'browse' => 'calls.browse',
                'update' => 'calls.update',
                'create' => 'calls.create'
            ],
            'datatable' => [
                'datatable' => AccountCallsDatatable::class
            ],
            'route' => [
                'linked' => 'accounts.calls.linked',
                'create' => 'calls.calls.create',
                'select' => 'accounts.calls.selection',
                'bind_selected' => 'accounts.calls.link'
            ],
            'create' => [
                'allow' => true,
                'modal_title' => 'calls::calls.create_new',
                'post_create_bind' => [
                    'relationType' => 'oneToMany',
                    'relatedField' => 'account_id',
                ]
            ],

            'select' => [
                'allow' => false,
                'modal_title' => 'calls::calls.module'
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
                'datatable' => AccountDealsDatatable::class
            ],
            'route' => [
                'linked' => 'accounts.deals.linked',
                'create' => 'deals.deals.create',
                'select' => 'accounts.deals.selection',
                'bind_selected' => 'accounts.deals.link'
            ],
            'create' => [
                'allow' => true,
                'modal_title' => 'deals::deals.create_new',
                'post_create_bind' => [
                    'relationType' => 'oneToMany',
                    'relatedField' => 'account_id',

                ]
            ],

            'select' => [
                'allow' => false,
                'modal_title' => 'tickets::tickets.module'
            ],
        ],
        'quotes' => [
            'icon' => 'chat',
            'permissions' => [
                'browse' => 'quotes.browse',
                'update' => 'quotes.update',
                'create' => 'quotes.create'
            ],
            'datatable' => [
                'datatable' => AccountQuotesDatatable::class
            ],
            'route' => [
                'linked' => 'accounts.quotes.linked',
                'create' => 'quotes.quotes.create',
                'select' => 'accounts.quotes.selection',
                'bind_selected' => 'accounts.quotes.link'
            ],
            'create' => [
                'allow' => true,
                'modal_title' => 'quotes::quotes.create_new',
                'post_create_bind' => [
                    'relationType' => 'oneToMany',
                    'relatedField' => 'account_id',

                ]
            ],

            'select' => [
                'allow' => false,
                'modal_title' => 'tickets::tickets.module'
            ],
        ],
        'orders' => [
            'icon' => 'pageview',
            'permissions' => [
                'browse' => 'orders.browse',
                'update' => 'orders.update',
                'create' => 'orders.create'
            ],
            'datatable' => [
                'datatable' => AccountOrdersDatatable::class
            ],
            'route' => [
                'linked' => 'accounts.orders.linked',
                'create' => 'orders.orders.create',
                'select' => 'accounts.orders.selection',
                'bind_selected' => 'accounts.orders.link'
            ],
            'create' => [
                'allow' => true,
                'modal_title' => 'orders::orders.create_new',
                'post_create_bind' => [
                    'relationType' => 'oneToMany',
                    'relatedField' => 'account_id',

                ]
            ],

            'select' => [
                'allow' => false,
                'modal_title' => 'orders::orders.module'
            ],
        ],

        'invoices' => [
            'icon' => 'shopping_cart',
            'permissions' => [
                'browse' => 'invoices.browse',
                'update' => 'invoices.update',
                'create' => 'invoices.create'
            ],
            'datatable' => [
                'datatable' => AccountInvoicesDatatable::class
            ],
            'route' => [
                'linked' => 'accounts.invoices.linked',
                'create' => 'invoices.invoices.create',
                'select' => 'accounts.invoices.selection',
                'bind_selected' => 'accounts.invoices.link'
            ],
            'create' => [
                'allow' => true,
                'modal_title' => 'invoices::invoices.create_new',
                'post_create_bind' => [
                    'relationType' => 'oneToMany',
                    'relatedField' => 'account_id',

                ]
            ],

            'select' => [
                'allow' => false,
                'modal_title' => 'invoices::invoices.module'
            ],
        ],

        'documents' => [
            'icon' => 'storage',
            'permissions' => [
                'browse' => 'documents.browse',
                'update' => 'documents.update',
                'create' => 'documents.create'
            ],
            'datatable' => [
                'datatable' => AccountDocumentsDatatable::class
            ],
            'route' => [
                'linked' => 'accounts.documents.linked',
                'create' => 'documents.documents.create',
                'select' => 'accounts.documents.selection',
                'bind_selected' => 'accounts.documents.link'
            ],
            'create' => [
                'allow' => true,
                'modal_title' => 'documents::documents.create_new',
                'post_create_bind' => [
                    'relationType' => 'manyToMany',
                    'relatedField' => 'accounts',
                ]
            ],

            'select' => [
                'allow' => true,
                'modal_title' => 'documents::documents.module'
            ],
        ],

        'campaigns' => [
            'icon' => 'show_chart',
            'permissions' => [
                'browse' => 'campaigns.browse',
                'update' => 'campaigns.update',
                'create' => 'campaigns.create'
            ],
            'datatable' => [
                'datatable' => AccountCampaignsDatatable::class
            ],
            'route' => [
                'linked' => 'accounts.campaigns.linked',
                'create' => 'campaigns.campaigns.create',
                'select' => 'accounts.campaigns.selection',
                'bind_selected' => 'accounts.campaigns.link'
            ],
            'create' => [
                'allow' => false,
                'modal_title' => 'campaigns::campaigns.create_new',
                'post_create_bind' => [
                    'relationType' => 'manyToMany',
                    'relatedField' => 'accounts',
                ]
            ],

            'select' => [
                'allow' => true,
                'modal_title' => 'campaigns::campaigns.module'
            ],
        ],

        'servicecontracts' => [
            'icon' => 'contact_mail',
            'permissions' => [
                'browse' => 'servicecontracts.browse',
                'update' => 'servicecontracts.update',
                'create' => 'servicecontracts.create'
            ],
            'datatable' => [
                'datatable' => AccountServiceContractsDatatable::class
            ],
            'route' => [
                'linked' => 'accounts.servicecontracts.linked',
                'create' => 'servicecontracts.servicecontracts.create',
                'select' => 'accounts.servicecontracts.selection',
                'bind_selected' => 'accounts.servicecontracts.link'
            ],
            'create' => [
                'allow' => true,
                'modal_title' => 'servicecontracts::servicecontracts.create_new',
                'post_create_bind' => [
                    'relationType' => 'oneToMany',
                    'relatedField' => 'account_id',
                ]
            ],

            'select' => [
                'allow' => false,
                'modal_title' => 'servicecontracts::servicecontracts.module'
            ],
        ],

        'assets' => [
            'icon' => 'laptop_chromebook',
            'permissions' => [
                'browse' => 'assets.browse',
                'update' => 'assets.update',
                'create' => 'assets.create'
            ],
            'datatable' => [
                'datatable' => AccountAssetsDatatable::class
            ],
            'route' => [
                'linked' => 'accounts.assets.linked',
                'create' => 'assets.assets.create',
                'select' => 'accounts.assets.selection',
                'bind_selected' => 'accounts.assets.link'
            ],
            'create' => [
                'allow' => true,
                'modal_title' => 'assets::assets.create_new',
                'post_create_bind' => [
                    'relationType' => 'oneToMany',
                    'relatedField' => 'account_id',
                ]
            ],

            'select' => [
                'allow' => false,
                'modal_title' => 'assets::assets.module'
            ],
        ],
    ];


    protected $showFields = [

        'information' => [

            'name' => [
                'type' => 'text',
            ],

            'tax_number' => [
                'type' => 'text'
            ],

            'owned_by' => [
                'type' => 'assigned_to',
            ],

            'website' => [
                'type' => 'text',
            ],

            'account_number' => [
                'type' => 'text',
            ],

            'annual_revenue' => [
                'type' => 'text',
            ],

            'employees' => [
                'type' => 'text',
            ],

            'account_type_id' => [
                'type' => 'manyToOne',
                'relation' => 'accountType',
                'column' => 'name'
            ],

            'account_industry_id' => [
                'type' => 'manyToOne',
                'relation' => 'accountIndustry',
                'column' => 'name'
            ],

            'account_rating_id' => [
                'type' => 'manyToOne',
                'relation' => 'accountRating',
                'column' => 'name'
            ],
        ],


        'contact_data' => [

            'phone' => [
                'type' => 'text',
            ],


            'email' => [
                'type' => 'text',
            ],


            'secondary_email' => [
                'type' => 'text',
            ],


            'fax' => [
                'type' => 'text',
            ],


            'skype_id' => [
                'type' => 'text',
            ],

        ],


        'address_information' => [

            'street' => [
                'type' => 'text',
            ],

            'city' => [
                'type' => 'text',
            ],

            'state' => [
                'type' => 'text',
            ],


            'country' => [
                'type' => 'text',
            ],


            'zip_code' => [
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

    protected $languageFile = 'accounts::accounts';

    protected $routes = [
        'index' => 'accounts.accounts.index',
        'create' => 'accounts.accounts.create',
        'show' => 'accounts.accounts.show',
        'edit' => 'accounts.accounts.edit',
        'store' => 'accounts.accounts.store',
        'destroy' => 'accounts.accounts.destroy',
        'update' => 'accounts.accounts.update',
        'import' => 'accounts.accounts.import',
        'import_process' =>  'accounts.accounts.import.process'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
