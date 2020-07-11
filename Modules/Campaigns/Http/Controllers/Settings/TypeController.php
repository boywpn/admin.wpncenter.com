<?php

namespace Modules\Campaigns\Http\Controllers\Settings;

use Modules\Campaigns\Datatables\Settings\CampaignTypeDatatable;
use Modules\Campaigns\Entities\CampaignType;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class TypeController extends ModuleCrudController
{
    protected $datatable = CampaignTypeDatatable::class;
    protected $formClass = NameDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = CampaignType::class;

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

    protected $languageFile = 'campaigns::campaigns.type';

    protected $routes = [
        'index' => 'campaigns.type.index',
        'create' => 'campaigns.type.create',
        'show' => 'campaigns.type.show',
        'edit' => 'campaigns.type.edit',
        'store' => 'campaigns.type.store',
        'destroy' => 'campaigns.type.destroy',
        'update' => 'campaigns.type.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
