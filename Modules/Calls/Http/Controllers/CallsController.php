<?php

namespace Modules\Calls\Http\Controllers;

use Modules\Calls\Datatables\CallDatatable;
use Modules\Calls\Entities\Call;
use Modules\Calls\Http\Forms\CallForm;
use Modules\Calls\Http\Requests\CallsRequest;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;

class CallsController extends ModuleCrudController
{

    protected $datatable = CallDatatable::class;
    protected $formClass = CallForm::class;
    protected $storeRequest = CallsRequest::class;
    protected $updateRequest = CallsRequest::class;
    protected $entityClass = Call::class;

    protected $moduleName = 'calls';

    protected $permissions = [
        'browse' => 'calls.browse',
        'create' => 'calls.create',
        'update' => 'calls.update',
        'destroy' => 'calls.destroy'
    ];

    protected $moduleSettingsLinks = [

        ['route' => 'calls.directiontype.index', 'label' => 'settings.directiontype'],


    ];

    protected $settingsPermission = 'calls.settings';

    protected $showFields = [

        'information' => [

            'owned_by' => [
                'type' => 'assigned_to',
            ],

            'call_date' => [
                'type' => 'text',
            ],

            'subject' => [
                'type' => 'text',
            ],

            'phone_number' => [
                'type' => 'text',
            ],


            'duration' => [
                'type' => 'text',
            ],

            'direction_id' => [
                'type' => 'manyToOne',
                'relation' => 'direction',
                'column' => 'name',
                'dont_translate' => true,
            ],


            'account_id' => [
                'type' => 'manyToOne',
                'relation' => 'account',
                'column' => 'name',
                'dont_translate' => true,

                'col-class' => 'col-lg-4 col-sm-4 col-md-4'
            ],


            'contact_id' => [
                'type' => 'manyToOne',
                'relation' => 'contact',
                'column' => 'full_name',
                'dont_translate' => true,

                'col-class' => 'col-lg-4 col-sm-4 col-md-4'
            ],


            'lead_id' => [
                'type' => 'manyToOne',
                'relation' => 'lead',
                'column' => 'full_name',
                'dont_translate' => true,

                'col-class' => 'col-lg-4 col-sm-4 col-md-4'
            ],



        ],


        'notes' => [

            'notes' => [
                'type' => 'text',
                'col-class' => 'col-lg-12 col-sm-12 col-md-12'
            ],

        ],


    ];

    protected $languageFile = 'calls::calls';

    protected $routes = [
        'index' => 'calls.calls.index',
        'create' => 'calls.calls.create',
        'show' => 'calls.calls.show',
        'edit' => 'calls.calls.edit',
        'store' => 'calls.calls.store',
        'destroy' => 'calls.calls.destroy',
        'update' => 'calls.calls.update'
    ];

    public function __construct()
    {
        parent::__construct();

    }

}
