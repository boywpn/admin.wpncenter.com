<?php

namespace Modules\Member\Members\Http\Controllers\Settings;

use Modules\Member\Members\Datatables\Settings\MembersStatusDatatable;
use Modules\Member\Members\Entities\MembersStatus;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Forms\NameIconDictionaryForm;
use Modules\Platform\Core\Http\Requests\NameDictionaryRequest;

class MembersStatusController extends ModuleCrudController
{
    protected $datatable = MembersStatusDatatable::class;
    protected $formClass = NameIconDictionaryForm::class;
    protected $storeRequest = NameDictionaryRequest::class;
    protected $updateRequest = NameDictionaryRequest::class;
    protected $entityClass = MembersStatus::class;

    protected $settingsBackRoute = 'member.members.index';

    protected $moduleName = 'CoreAgents';

    protected $permissions = [
        'browse' => 'member.members.settings',
        'create' => 'member.members.settings',
        'update' => 'member.members.settings',
        'destroy' => 'member.members.settings'
    ];

    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text', 'col-class' => 'col-lg-4'],
            'icon' => ['type' => 'text', 'col-class' => 'col-lg-4'],
            'color' => ['type' => 'text', 'col-class' => 'col-lg-4'],
        ]
    ];

    protected $languageFile = 'member/members::members.status';

    protected $routes = [
        'index' => 'member.members.status.index',
        'create' => 'member.members.status.create',
        'show' => 'member.members.status.show',
        'edit' => 'member.members.status.edit',
        'store' => 'member.members.status.store',
        'destroy' => 'member.members.status.destroy',
        'update' => 'member.members.status.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
