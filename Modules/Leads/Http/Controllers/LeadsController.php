<?php

namespace Modules\Leads\Http\Controllers;


use Modules\Leads\Datatables\LeadsDatatable;
use Modules\Leads\Datatables\Tabs\LeadCallsDatatable;
use Modules\Leads\Datatables\Tabs\LeadCampaignsDatatable;
use Modules\Leads\Datatables\Tabs\LeadDocumentsDatatable;
use Modules\Leads\Datatables\Tabs\LeadEmailDatatable;
use Modules\Leads\Datatables\Tabs\LeadProductsDatatable;
use Modules\Leads\Entities\Lead;
use Modules\Leads\Http\Forms\LeadForm;
use Modules\Leads\Http\Requests\CreateLeadRequest;
use Modules\Leads\Http\Requests\LeadRequest;
use Modules\Leads\Http\Requests\UpdateLeadRequest;
use Modules\Leads\Service\LeadService;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;

class LeadsController extends ModuleCrudController
{
    protected $datatable = LeadsDatatable::class;
    protected $formClass = LeadForm::class;
    protected $storeRequest = CreateLeadRequest::class;
    protected $updateRequest = UpdateLeadRequest::class;
    protected $entityClass = Lead::class;

    protected $moduleName = 'leads';

    protected $permissions = [
        'browse' => 'leads.browse',
        'create' => 'leads.create',
        'update' => 'leads.update',
        'destroy' => 'leads.destroy'
    ];

    protected $moduleSettingsLinks = [
        ['route' => 'leads.status.index', 'label' => 'settings.status'],
        ['route' => 'leads.source.index', 'label' => 'settings.source'],
        ['route' => 'leads.rating.index', 'label' => 'settings.rating'],
        ['route' => 'leads.industry.index', 'label' => 'settings.industry'],
    ];

    protected function setupActionButtons()
    {
        $this->actionButtons[] = array(
            'href' => route($this->routes['create'], ['copy' => $this->entity->id]),
            'attr' => [

            ],
            'label' => trans('core::core.btn.copy')
        );
        $this->actionButtons[] = array(
            'href' => route('leads.leads.convert.to.contact', ['id' => $this->entity->id]),
            'attr' => [

            ],
            'label' => trans('leads::leads.convert_to_contact')
        );
    }


    public function convertToContact($leadId){

        if ($this->permissions['create'] != '' && !\Auth::user()->hasPermissionTo($this->permissions['create'])) {
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        }

        $leadService = \App::make(LeadService::class);

        $contact = $leadService->convertToContact($leadId);

        if(!empty($contact)){
            flash(trans('core::core.record_converted'))->success();

           return redirect()->route('contacts.contacts.show',$contact->id);
        }

        flash(trans('core::core.error_while_converting'))->error();
        return redirect()->route($this->routes['index']);

    }


    protected $relationTabs = [

        'multi_email' => [
            'icon' => 'contact_mail',
            'permissions' => [
                'browse' => 'leads.browse',
                'update' => 'leads.update',
                'create' => 'leads.create'
            ],
            'datatable' => [
                'datatable' => LeadEmailDatatable::class
            ],
            'route' => [
                'linked' => 'leads.leademails.linked',
                'create' => 'leademails.leademails.create',
                'select' => 'leads.leademails.selection',
                'bind_selected' => 'leads.leademails.link'
            ],
            'create' => [
                'allow' => true,
                'modal_title' => 'leademails::leademails.create_new',
                'post_create_bind' => [
                    'relationType' => 'oneToMany',
                    'relatedField' => 'lead_id',
                ]
            ],

            'select' => [
                'allow' => false,
                'modal_title' => 'leademails::leademails.module'
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
                'datatable' => LeadCallsDatatable::class
            ],
            'route' => [
                'linked' => 'leads.calls.linked',
                'create' => 'calls.calls.create',
                'select' => 'leads.calls.selection',
                'bind_selected' => 'leads.calls.link'
            ],
            'create' => [
                'allow' => true,
                'modal_title' => 'calls::calls.create_new',
                'post_create_bind' => [
                    'relationType' => 'oneToMany',
                    'relatedField' => 'lead_id',
                ]
            ],

            'select' => [
                'allow' => false,
                'modal_title' => 'calls::calls.module'
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
                'datatable' => LeadDocumentsDatatable::class
            ],
            'route' => [
                'linked' => 'leads.documents.linked',
                'create' => 'documents.documents.create',
                'select' => 'leads.documents.selection',
                'bind_selected' => 'leads.documents.link'
            ],
            'create' => [
                'allow' => true,
                'modal_title' => 'documents::documents.create_new',
                'post_create_bind' => [
                    'relationType' => 'manyToMany',
                    'relatedField' => 'leads',
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
                'datatable' => LeadCampaignsDatatable::class
            ],
            'route' => [
                'linked' => 'leads.campaigns.linked',
                'create' => 'campaigns.campaigns.create',
                'select' => 'leads.campaigns.selection',
                'bind_selected' => 'leads.campaigns.link'
            ],
            'create' => [
                'allow' => false,
                'modal_title' => 'campaigns::campaigns.create_new',
                'post_create_bind' => [
                    'relationType' => 'manyToMany',
                    'relatedField' => 'leads',
                ]
            ],

            'select' => [
                'allow' => true,
                'modal_title' => 'campaigns::campaigns.module'
            ],

        ],

        'products' => [
            'icon' => 'pageview',
            'permissions' => [
                'browse' => 'products.browse',
                'update' => 'products.update',
                'create' => 'products.create'
            ],
            'datatable' => [
                'datatable' => LeadProductsDatatable::class
            ],
            'route' => [
                'linked' => 'leads.products.linked',
                'create' => 'products.products.create',
                'select' => 'leads.products.selection',
                'bind_selected' => 'leads.products.link'
            ],
            'create' => [
                'allow' => false,
                'modal_title' => 'products::products.create_new',
                'post_create_bind' => [
                    'relationType' => 'manyToMany',
                    'relatedField' => 'leads',
                ]
            ],

            'select' => [
                'allow' => true,
                'modal_title' => 'products::products.module'
            ],

        ],


    ];

    protected $settingsPermission = 'leads.settings';

    protected $showFields = [

        'lead_information' => [
            'owned_by' => ['type' => 'assigned_to', 'col-class' => 'col-lg-4'],
            'capture_date' => [
                'type' => 'text',
                'col-class' => 'col-lg-4'
            ],
            'first_name' => ['type' => 'text', 'col-class' => 'col-lg-4'],
            'last_name' => ['type' => 'text', 'col-class' => 'col-lg-4'],
            'annual_revenue' => ['type' => 'text', 'col-class' => 'col-lg-4'],

            'lead_status_id' => [
                'type' => 'manyToOne',
                'relation' => 'leadStatus',
                'column' => 'name',
                'col-class' => 'col-lg-4'
            ],
            'lead_source_id' => [
                'type' => 'manyToOne',
                'relation' => 'leadSource',
                'column' => 'name',
                'col-class' => 'col-lg-4'
            ],
            'lead_industry_id' => [
                'type' => 'manyToOne',
                'relation' => 'leadIndustry',
                'column' => 'name',
                'col-class' => 'col-lg-4'
            ],
            'lead_rating_id' => [
                'type' => 'manyToOne',
                'relation' => 'leadRating',
                'column' => 'name',
                'col-class' => 'col-lg-4'
            ],
        ],
        'lead_contact_data' => [
            'phone' => ['type' => 'text'],
            'mobile' => ['type' => 'text'],
            'email' => ['type' => 'text'],
            'secondary_email' => ['type' => 'text'],
            'fax' => ['type' => 'text'],
        ],

        'lead_company' => [
            'job_title' => ['type' => 'text'],
            'website' => ['type' => 'text'],
            'lead_company' => ['type' => 'text'],
            'no_of_employees' => ['type' => 'text'],

        ],


        'address_information' => [
            'addr_street' => ['type' => 'text', 'col-class' => 'col-lg-12'],
            'addr_state' => ['type' => 'text', 'col-class' => 'col-lg-6'],
            'addr_country' => ['type' => 'text', 'col-class' => 'col-lg-6'],
            'addr_city' => ['type' => 'text', 'col-class' => 'col-lg-6'],
            'addr_zip' => ['type' => 'text', 'col-class' => 'col-lg-6'],
        ],
        'social' => [
            'skype' => ['type' => 'text', 'col-class' => 'col-lg-4'],
            'facebook' => ['type' => 'text', 'col-class' => 'col-lg-4'],
            'twitter' => ['type' => 'text', 'col-class' => 'col-lg-4'],
        ],

        'description' => [
            'description' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ],

    ];

    protected $languageFile = 'leads::leads';

    protected $routes = [
        'index' => 'leads.leads.index',
        'create' => 'leads.leads.create',
        'show' => 'leads.leads.show',
        'edit' => 'leads.leads.edit',
        'store' => 'leads.leads.store',
        'destroy' => 'leads.leads.destroy',
        'update' => 'leads.leads.update',
        'import' => 'leads.leads.import',
        'import_process' =>  'leads.leads.import.process'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
