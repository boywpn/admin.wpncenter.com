<?php

namespace Modules\Platform\User\Http\Controllers\Roles;

use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\User\Datatables\RolesDatatable;
use Modules\Platform\User\Http\Forms\RoleForm;
use Modules\Platform\User\Http\Requests\RoleCreateRequest;
use Modules\Platform\User\Http\Requests\RoleUpdateRequest;
use Modules\Platform\User\Repositories\RoleRepository;
use Spatie\Permission\Models\Role;

class RolesController extends ModuleCrudController
{
    protected $demoMode = true;

    protected $settingsMode = true;

    protected $datatable = RolesDatatable::class;
    protected $formClass = RoleForm::class;
    protected $storeRequest = RoleCreateRequest::class;
    protected $updateRequest = RoleUpdateRequest::class;
    protected $repository = RoleRepository::class;

    protected $moduleName = 'user';

    protected $entityClass = Role::class;

    protected $showFields = [
        'details' => [
            'display_name' => ['type' => 'text'],
            'name' => ['type' => 'text'],
            'guard_name' => ['type' => 'text'],
        ]
    ];

    protected function setupCustomButtons()
    {
        $this->customShowButtons[] = array(
            'href' => route('settings.roles.permissions', $this->entity->id),
            'attr' => [
                'class' => 'btn bg-pink waves-effect',
            ],
            'label' => trans('user::roles.setup_permissions'),
        );
    }

    protected $languageFile = 'user::roles';

    protected $routes = [
        'index' => 'settings.roles.index',
        'create' => 'settings.roles.create',
        'show' => 'settings.roles.show',
        'edit' => 'settings.roles.edit',
        'store' => 'settings.roles.store',
        'destroy' => 'settings.roles.destroy',
        'update' => 'settings.roles.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }


}
