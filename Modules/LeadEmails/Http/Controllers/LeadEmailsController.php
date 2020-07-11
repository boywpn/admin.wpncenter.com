<?php

namespace Modules\LeadEmails\Http\Controllers;

use Illuminate\Http\Request;
use Modules\LeadEmails\Datatables\LeadEmailDatatable;
use Modules\LeadEmails\Entities\LeadEmail;
use Modules\LeadEmails\Http\Forms\LeadEmailForm;
use Modules\LeadEmails\Http\Requests\CreateLeadEmailsRequest;
use Modules\LeadEmails\Http\Requests\UpdateLeadEmailsRequest;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;

class LeadEmailsController extends ModuleCrudController
{

    protected $datatable = LeadEmailDatatable::class;
    protected $formClass = LeadEmailForm::class;
    protected $storeRequest = CreateLeadEmailsRequest::class;
    protected $updateRequest = UpdateLeadEmailsRequest::class;
    protected $entityClass = LeadEmail::class;

    protected $moduleName = 'leademails';

    protected $permissions = [
        'browse' => 'leads.browse',
        'create' => 'leads.create',
        'update' => 'leads.update',
        'destroy' => 'leads.destroy'
    ];

    protected $showFields = [

        'information' => [

            'email' => [
                'type' => 'text',
                'col-class' => 'col-lg-12 col-sm-12 col-md-12'
            ],


            'is_default' => [
                'type' => 'boolean',
                'col-class' => 'col-lg-4 col-sm-4 col-md-4'
            ],


            'is_active' => [
                'type' => 'boolean',
                'col-class' => 'col-lg-4 col-sm-4 col-md-4'
            ],


            'is_marketing' => [
                'type' => 'boolean',
                'col-class' => 'col-lg-4 col-sm-4 col-md-4'
            ],


            'lead_id' => [
                'type' => 'manyToOne',
                'relation' => 'lead',
                'column' => 'full_name',
                'dont_translate' => true
            ],

        ],


        'notes' => [

            'notes' => [
                'type' => 'text',
                'col-class' => 'col-lg-12 col-sm-12 col-md-12'
            ],

        ],


    ];

    protected $languageFile = 'leademails::leademails';

    protected $routes = [
        'index' => 'leademails.leademails.index',
        'create' => 'leademails.leademails.create',
        'show' => 'leademails.leademails.show',
        'edit' => 'leademails.leademails.edit',
        'store' => 'leademails.leademails.store',
        'destroy' => 'leademails.leademails.destroy',
        'update' => 'leademails.leademails.update'
    ];

    public function index(Request $request)
    {
        return redirect()->route('leads.leads.index');
    }

    public function __construct()
    {
        parent::__construct();

    }

}
