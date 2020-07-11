<?php

namespace Modules\Platform\Settings\Http\Controllers;

use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Settings\Datatables\TaxDatatable;
use Modules\Platform\Settings\Entities\Tax;
use Modules\Platform\Settings\Http\Forms\TaxForm;
use Modules\Platform\Settings\Http\Requests\TaxSettingsRequest;
use Modules\Platform\Settings\Repositories\TaxRepository;

/**
 * Class TaxController
 * @package Modules\Platform\Settings\Http\Controllers
 */
class TaxController extends ModuleCrudController
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $settingsMode = true;

    protected $disableTabs = true;

    protected $moduleName = 'settings';

    protected $permissions = [
        'browse' => 'settings.access',
        'create' => 'settings.access',
        'update' => 'settings.access',
        'destroy' => 'settings.access'
    ];

    protected $entityClass = Tax::class;


    protected $datatable = TaxDatatable::class;

    protected $formClass = TaxForm::class;

    protected $storeRequest = TaxSettingsRequest::class;

    protected $updateRequest = TaxSettingsRequest::class;

    protected $repository = TaxRepository::class;

    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text'],
            'tax_value' => ['type' => 'text'],
        ]
    ];


    protected $languageFile = 'settings::tax';


    protected $routes = [
        'index' => 'settings.tax.index',
        'create' => 'settings.tax.create',
        'show' => 'settings.tax.show',
        'edit' => 'settings.tax.edit',
        'store' => 'settings.tax.store',
        'destroy' => 'settings.tax.destroy',
        'update' => 'settings.tax.update'
    ];
}
