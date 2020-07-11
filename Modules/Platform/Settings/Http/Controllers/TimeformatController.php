<?php

namespace Modules\Platform\Settings\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Krucas\Settings\Facades\Settings;
use Modules\Platform\Core\Http\Controllers\AppBaseController;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Controllers\SettingsCrudController;
use Modules\Platform\Settings\Datatables\DateformatDatatable;
use Modules\Platform\Settings\Datatables\TimeformatDatatable;
use Modules\Platform\Settings\Http\Forms\DateformatForm;
use Modules\Platform\Settings\Http\Forms\TimeformatForm;
use Modules\Platform\Settings\Http\Requests\DateformatSettingsRequest;
use Modules\Platform\Settings\Http\Requests\TimeformatSettingsRequest;
use Modules\Platform\Settings\Repositories\DateformatRepository;
use Modules\Platform\Settings\Repositories\TimeformatRepository;
use Modules\Platform\User\Entities\TimeFormat;

class TimeformatController extends ModuleCrudController
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

    protected $entityClass = TimeFormat::class;

    protected $datatable = TimeformatDatatable::class;

    protected $formClass = TimeformatForm::class;

    protected $storeRequest = TimeformatSettingsRequest::class;

    protected $updateRequest = TimeformatSettingsRequest::class;

    protected $repository = TimeformatRepository::class;


    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text'],
            'details' => ['type' => 'text'],
            'js_details' => ['type' => 'text'],
        ]
    ];

    protected $languageFile = 'settings::timeformat';


    protected $routes = [
        'index' => 'settings.timeformat.index',
        'create' => 'settings.timeformat.create',
        'show' => 'settings.timeformat.show',
        'edit' => 'settings.timeformat.edit',
        'store' => 'settings.timeformat.store',
        'destroy' => 'settings.timeformat.destroy',
        'update' => 'settings.timeformat.update'
    ];
}
