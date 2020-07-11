<?php

namespace Modules\ContactRequests\Http\Controllers;

use Modules\ContactRequests\Datatables\ContactRequestDatatable;
use Modules\ContactRequests\Entities\ContactRequest;
use Modules\ContactRequests\Http\Forms\ContactRequestForm;
use Modules\ContactRequests\Http\Requests\ContactRequestsRequest;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;

class ContactRequestsController extends ModuleCrudController
{

    protected $datatable = ContactRequestDatatable::class;
    protected $formClass = ContactRequestForm::class;
    protected $storeRequest = ContactRequestsRequest::class;
    protected $updateRequest = ContactRequestsRequest::class;
    protected $entityClass = ContactRequest::class;

    protected $moduleName = 'contactrequests';

    protected $permissions = [
        'browse' => 'contactrequests.browse',
        'create' => 'contactrequests.create',
        'update' => 'contactrequests.update',
        'destroy' => 'contactrequests.destroy'
    ];

    protected $moduleSettingsLinks = [

        ['route' => 'contactrequests.contactrequeststatus.index', 'label' => 'settings.contactrequeststatus'],
        ['route' => 'contactrequests.preferredcontactmethod.index', 'label' => 'settings.preferredcontactmethod'],
        ['route' => 'contactrequests.contactreason.index', 'label' => 'settings.contactreason'],


    ];

    protected $settingsPermission = 'contactrequests.settings';

    protected $showFields = [

        'information' => [

            'first_name' => [
                'type' => 'text',
                'col-class' => 'col-lg-4 col-sm-4 col-md-4'
            ],


            'last_name' => [
                'type' => 'text',
                'col-class' => 'col-lg-4 col-sm-4 col-md-4'
            ],


            'organization_name' => [
                'type' => 'text',
                'col-class' => 'col-lg-4 col-sm-4 col-md-4'
            ],


            'phone_number' => [
                'type' => 'text',
                'col-class' => 'col-lg-4 col-sm-4 col-md-4'
            ],


            'email' => [
                'type' => 'text',
                'col-class' => 'col-lg-4 col-sm-4 col-md-4'
            ],


            'other_contact_method' => [
                'type' => 'text',
                'col-class' => 'col-lg-4 col-sm-4 col-md-4'
            ],


            'custom_subject' => [
                'type' => 'text',
                'col-class' => 'col-lg-12 col-sm-12 col-md-12'
            ],


            'contact_date' => [
                'type' => 'text',
                'col-class' => 'col-lg-6 col-sm-6 col-md-6'
            ],


            'next_contact_date' => [
                'type' => 'text',
                'col-class' => 'col-lg-6 col-sm-6 col-md-6'
            ],


            'status_id' => [
                'type' => 'manyToOne',
                'relation' => 'status',
                'column' => 'name',
                'col-class' => 'col-lg-3 col-sm-3 col-md-3'
            ],


            'preferred_id' => [
                'type' => 'manyToOne',
                'relation' => 'preferred',
                'column' => 'name',
                'col-class' => 'col-lg-3 col-sm-3 col-md-3'
            ],

            'contact_reason_id' => [
                'type' => 'manyToOne',
                'relation' => 'contactReason',
                'column' => 'name',
                'col-class' => 'col-lg-3 col-sm-3 col-md-3'
            ],


            'owned_by' => [
                'type' => 'assigned_to',
                'col-class' => 'col-lg-3 col-sm-3 col-md-3'
            ],

        ],


        'notes' => [

            'notes' => [
                'type' => 'text',
                'col-class' => 'col-lg-12 col-sm-12 col-md-12'
            ],

        ],


    ];

    protected $languageFile = 'contactrequests::contactrequests';

    protected $routes = [
        'index' => 'contactrequests.contactrequests.index',
        'create' => 'contactrequests.contactrequests.create',
        'show' => 'contactrequests.contactrequests.show',
        'edit' => 'contactrequests.contactrequests.edit',
        'store' => 'contactrequests.contactrequests.store',
        'destroy' => 'contactrequests.contactrequests.destroy',
        'update' => 'contactrequests.contactrequests.update'
    ];

    public function __construct()
    {
        parent::__construct();

    }

}
