<?php

namespace Modules\Vendors\Http\Controllers;

use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Vendors\Datatables\VendorDatatable;
use Modules\Vendors\Entities\Vendor;
use Modules\Vendors\Http\Forms\VendorForm;
use Modules\Vendors\Http\Requests\VendorsRequest;

class VendorsController extends ModuleCrudController
{
    protected $datatable = VendorDatatable::class;
    protected $formClass = VendorForm::class;
    protected $storeRequest = VendorsRequest::class;
    protected $updateRequest = VendorsRequest::class;
    protected $entityClass = Vendor::class;

    protected $moduleName = 'vendors';


    protected $permissions = [
        'browse' => 'vendors.browse',
        'create' => 'vendors.create',
        'update' => 'vendors.update',
        'destroy' => 'vendors.destroy'
    ];

    protected $moduleSettingsLinks = [

        ['route' => 'vendors.category.index', 'label' => 'settings.category'],


    ];

    protected $settingsPermission = 'vendors.settings';

    protected $showFields = [

        'information' => [

            'name' => [
                'type' => 'text',
            ],


            'owned_by' => [
                'type' => 'assigned_to',
            ],


            'vendor_category_id' => [
                'type' => 'manyToOne',
                'relation' => 'vendorCategory',
                'column' => 'name'
            ],
        ],


        'contact_data' => [

            'phone' => [
                'type' => 'text',
            ],


            'mobile' => [
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

    protected $languageFile = 'vendors::vendors';

    protected $routes = [
        'index' => 'vendors.vendors.index',
        'create' => 'vendors.vendors.create',
        'show' => 'vendors.vendors.show',
        'edit' => 'vendors.vendors.edit',
        'store' => 'vendors.vendors.store',
        'destroy' => 'vendors.vendors.destroy',
        'update' => 'vendors.vendors.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
