<?php

namespace Modules\Assets\Http\Controllers;

use Modules\Assets\Datatables\AssetDatatable;
use Modules\Assets\Entities\Asset;
use Modules\Assets\Http\Forms\AssetForm;
use Modules\Assets\Http\Requests\AssetsRequest;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;

class AssetsController extends ModuleCrudController
{
    protected $datatable = AssetDatatable::class;
    protected $formClass = AssetForm::class;
    protected $storeRequest = AssetsRequest::class;
    protected $updateRequest = AssetsRequest::class;
    protected $entityClass = Asset::class;

    protected $moduleName = 'assets';

    protected $permissions = [
        'browse' => 'assets.browse',
        'create' => 'assets.create',
        'update' => 'assets.update',
        'destroy' => 'assets.destroy'
    ];

    protected $moduleSettingsLinks = [

        ['route' => 'assets.status.index', 'label' => 'settings.status'],
        ['route' => 'assets.category.index', 'label' => 'settings.category'],
        ['route' => 'assets.manufacturer.index', 'label' => 'settings.manufacturer'],


    ];

    protected $settingsPermission = 'assets.settings';

    protected $showFields = [

        'information' => [

            'name' => [
                'type' => 'text',
            ],


            'model_no' => [
                'type' => 'text',
            ],


            'tag_number' => [
                'type' => 'text',
            ],


            'order_number' => [
                'type' => 'text',
            ],


            'purchase_date' => [
                'type' => 'date',
            ],


            'purchase_cost' => [
                'type' => 'decimal',
            ],


            'owned_by' => [
                'type' => 'assigned_to',
            ],


            'asset_status_id' => [
                'type' => 'manyToOne',
                'relation' => 'assetStatus',
                'column' => 'name'
            ],


            'asset_category_id' => [
                'type' => 'manyToOne',
                'relation' => 'assetCategory',
                'column' => 'name'
            ],

            'contact_id' => [
                'type' => 'manyToOne',
                'relation' => 'contact',
                'column' => 'full_name',
                'dont_translate' => true
            ],

            'account_id' => [
                'type' => 'manyToOne',
                'relation' => 'account',
                'column' => 'name',
                'dont_translate' => true
            ],



            'asset_manufacturer_id' => [
                'type' => 'manyToOne',
                'relation' => 'assetManufacturer',
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

    protected $languageFile = 'assets::assets';

    protected $routes = [
        'index' => 'assets.assets.index',
        'create' => 'assets.assets.create',
        'show' => 'assets.assets.show',
        'edit' => 'assets.assets.edit',
        'store' => 'assets.assets.store',
        'destroy' => 'assets.assets.destroy',
        'update' => 'assets.assets.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
