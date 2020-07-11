<?php

namespace Modules\Platform\Settings\Http\Controllers;

use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Settings\Datatables\DateformatDatatable;
use Modules\Platform\Settings\Http\Forms\DateformatForm;
use Modules\Platform\Settings\Http\Requests\DateformatSettingsRequest;
use Modules\Platform\Settings\Repositories\DateformatRepository;
use Modules\Platform\User\Entities\DateFormat;

class DateformatController extends ModuleCrudController
{
    protected $datatable = DateformatDatatable::class;
    protected $formClass = DateformatForm::class;
    protected $storeRequest = DateformatSettingsRequest::class;
    protected $updateRequest = DateformatSettingsRequest::class;
    protected $repository = DateformatRepository::class;

    protected $settingsMode = true;

    protected $disableTabs = true;

    protected $moduleName = 'settings';

    protected $permissions = [
        'browse' => 'settings.access',
        'create' => 'settings.access',
        'update' => 'settings.access',
        'destroy' => 'settings.access'
    ];

    protected $entityClass = DateFormat::class;
    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text'],
            'details' => ['type' => 'text'],
            'js_details' => ['type' => 'text'],
        ]
    ];

    protected $languageFile = 'settings::dateformat';

    protected $routes = [
        'index' => 'settings.dateformat.index',
        'create' => 'settings.dateformat.create',
        'show' => 'settings.dateformat.show',
        'edit' => 'settings.dateformat.edit',
        'store' => 'settings.dateformat.store',
        'destroy' => 'settings.dateformat.destroy',
        'update' => 'settings.dateformat.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
