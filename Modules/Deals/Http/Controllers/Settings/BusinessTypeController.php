<?php

namespace Modules\Deals\Http\Controllers\Settings;

use Modules\Deals\Datatables\Settings\DealBusinessTypeDatatable;
use Modules\Deals\Entities\DealBusinessType;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class BusinessTypeController extends ModuleCrudController
{
    protected $datatable = DealBusinessTypeDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = DealBusinessType::class;

    protected $moduleName = 'deals';

    protected $permissions = [
        'browse' => 'deals.settings',
        'create' => 'deals.settings',
        'update' => 'deals.settings',
        'destroy' => 'deals.settings'
    ];

    protected $settingsBackRoute = 'deals.deals.index';

    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];

    protected $languageFile = 'deals::deals.businesstype';

    protected $routes = [
        'index' => 'deals.businesstype.index',
        'create' => 'deals.businesstype.create',
        'show' => 'deals.businesstype.show',
        'edit' => 'deals.businesstype.edit',
        'store' => 'deals.businesstype.store',
        'destroy' => 'deals.businesstype.destroy',
        'update' => 'deals.businesstype.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
