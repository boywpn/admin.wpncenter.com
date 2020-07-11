<?php

namespace Modules\Core\Agents\Http\Controllers\Settings;

use Modules\Core\Agents\Datatables\Settings\AgentsStatusDatatable;
use Modules\Core\Agents\Entities\AgentsStatus;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameIconDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class AgentsStatusController extends ModuleCrudController
{
    protected $datatable = AgentsStatusDatatable::class;
    protected $formClass = NameIconDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = AgentsStatus::class;

    protected $settingsBackRoute = 'core.agents.index';

    protected $moduleName = 'CoreAgents';

    protected $permissions = [
        'browse' => 'core.agents.settings',
        'create' => 'core.agents.settings',
        'update' => 'core.agents.settings',
        'destroy' => 'core.agents.settings'
    ];

    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text', 'col-class' => 'col-lg-4'],
            'icon' => ['type' => 'text', 'col-class' => 'col-lg-4'],
            'color' => ['type' => 'text', 'col-class' => 'col-lg-4'],
        ]
    ];

    protected $languageFile = 'core/agents::agents.status';

    protected $routes = [
        'index' => 'core.agents.status.index',
        'create' => 'core.agents.status.create',
        'show' => 'core.agents.status.show',
        'edit' => 'core.agents.status.edit',
        'store' => 'core.agents.status.store',
        'destroy' => 'core.agents.status.destroy',
        'update' => 'core.agents.status.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
