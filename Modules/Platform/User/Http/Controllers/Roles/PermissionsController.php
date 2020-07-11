<?php

namespace Modules\Platform\User\Http\Controllers\Roles;

use Modules\Platform\Core\Http\Controllers\AppBaseController;
use Modules\Platform\User\Http\Requests\SetupPermissionsRequest;
use Modules\Platform\User\Repositories\RoleRepository;
use Ouzo\Utilities\Arrays;
use Ouzo\Utilities\Functions;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Class PermissionsController
 * @package Modules\Platform\User\Http\Controllers\Roles
 */
class PermissionsController extends AppBaseController
{

    /**
     * @var RoleRepository
     */
    private $roleRepo;

    /**
     * PermissionsController constructor.
     * @param RoleRepository $repository
     */
    public function __construct(RoleRepository $repository)
    {
        parent::__construct();
        $this->roleRepo = $repository;
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function setup($id)
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Load permissions from config files
        $this->roleRepo->synchModulePermissions(true);

        $view = view('user::roles.permissions');

        $role = $this->roleRepo->find($id);

        if (empty($role)) {
            flash(trans('core::core.general_exception'))->error();
            return redirect(route('settings.roles.index'));
        }

        $view->with('role', $role);
        $view->with('permissions', $this->roleRepo->getGroupedAllPermissions());
        $view->with('rolePermissions', $role->permissions()->pluck('name')->toArray());

        return $view;
    }

    /**
     * @param $id
     * @param SetupPermissionsRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function save($id, SetupPermissionsRequest $request)
    {
        if (config('bap.demo')) {
            flash(trans('core::core.you_cant_do_that_its_demo'))->error();
            return redirect()->back();
        }

        $role = $this->roleRepo->find($id);

        if (empty($role)) {
            flash(trans('core::core.general_exception'))->error();
            return redirect(route('settings.roles.index'));
        }

        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        if ($request->get('permissions') != null) {
            $role->syncPermissions($request->get('permissions'));
        } else {
            $role->syncPermissions([]);
        }

        flash(trans('user::roles.permissions_updated'))->success();

        return redirect()->route('settings.roles.permissions', $id);
    }
}
