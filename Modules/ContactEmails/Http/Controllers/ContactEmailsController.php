<?php

namespace Modules\ContactEmails\Http\Controllers;

use Illuminate\Http\Request;
use Modules\ContactEmails\Datatables\ContactEmailDatatable;
use Modules\ContactEmails\Entities\ContactEmail;
use Modules\ContactEmails\Http\Forms\ContactEmailForm;
use Modules\ContactEmails\Http\Requests\ContactEmailsRequest;
use Modules\ContactEmails\Http\Requests\CreateContactEmailsRequest;
use Modules\ContactEmails\Http\Requests\UpdateContactEmailsRequest;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;

class ContactEmailsController extends ModuleCrudController
{

    protected $datatable = ContactEmailDatatable::class;
    protected $formClass = ContactEmailForm::class;
    protected $storeRequest = CreateContactEmailsRequest::class;
    protected $updateRequest = UpdateContactEmailsRequest::class;
    protected $entityClass = ContactEmail::class;

    protected $moduleName = 'contactemails';

    protected $permissions = [
        'browse' => 'contacts.browse',
        'create' => 'contacts.create',
        'update' => 'contacts.update',
        'destroy' => 'contacts.destroy'
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


            'contact_id' => [
                'type' => 'manyToOne',
                'relation' => 'contact',
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

    protected $languageFile = 'contactemails::contactemails';

    protected $routes = [
        'index' => 'contactemails.contactemails.index',
        'create' => 'contactemails.contactemails.create',
        'show' => 'contactemails.contactemails.show',
        'edit' => 'contactemails.contactemails.edit',
        'store' => 'contactemails.contactemails.store',
        'destroy' => 'contactemails.contactemails.destroy',
        'update' => 'contactemails.contactemails.update'
    ];

    public function index(Request $request)
    {
        return redirect()->route('contacts.contacts.index');
    }

    public function __construct()
    {
        parent::__construct();

    }

}
