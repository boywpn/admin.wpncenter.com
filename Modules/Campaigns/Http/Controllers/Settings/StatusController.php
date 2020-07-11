<?php

namespace Modules\Campaigns\Http\Controllers\Settings;

use Modules\Campaigns\Datatables\Settings\CampaignStatusDatatable;
use Modules\Campaigns\Entities\CampaignStatus;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Forms\NameIconDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class StatusController extends ModuleCrudController
{
    protected $datatable = CampaignStatusDatatable::class;
    protected $formClass = NameIconDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = CampaignStatus::class;

    protected $disableWidgets = true;

    protected $moduleName = 'campaigns';

    protected $permissions = [
        'browse' => 'campaigns.settings',
        'create' => 'campaigns.settings',
        'update' => 'campaigns.settings',
        'destroy' => 'campaigns.settings'
    ];

    protected $settingsBackRoute = 'campaigns.campaigns.index';

    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text', 'col-class' => 'col-lg-12'],
        ]
    ];

    protected $languageFile = 'campaigns::campaigns.status';

    protected $routes = [
        'index' => 'campaigns.status.index',
        'create' => 'campaigns.status.create',
        'show' => 'campaigns.status.show',
        'edit' => 'campaigns.status.edit',
        'store' => 'campaigns.status.store',
        'destroy' => 'campaigns.status.destroy',
        'update' => 'campaigns.status.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
