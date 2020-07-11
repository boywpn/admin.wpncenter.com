<?php

namespace Modules\Platform\User\Http\Controllers;

use App\Http\Requests\Request;
use Modules\Platform\Companies\Datatables\Scope\CurrentCompanyScope;
use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\Platform\Core\Http\Controllers\SettingsCrudController;
use Modules\Platform\User\Datatables\GroupsDatatable;
use Modules\Platform\User\Entities\Group;
use Modules\Platform\User\Http\Forms\GroupForm;
use Modules\Platform\User\Http\Requests\GroupCreateRequest;
use Modules\Platform\User\Http\Requests\GroupUpdateRequest;
use Modules\Platform\User\Repositories\GroupRepository;
use Modules\Platform\User\Repositories\UserRepository;

class GroupsController extends ModuleCrudController
{
    protected $datatable = GroupsDatatable::class;
    protected $formClass = GroupForm::class;
    protected $storeRequest = GroupCreateRequest::class;
    protected $updateRequest = GroupUpdateRequest::class;
    protected $repository = GroupRepository::class;

    protected $demoMode = true;

    protected $settingsMode = true;

    protected $disableTabs = true;

    protected $moduleName = 'settings';

    protected $permissions = [
        'browse' => '',
        'create' => '',
        'update' => '',
        'destroy' => ''
    ];

    protected $entityClass = Group::class;

    protected $showFields = [
        'details' => [
            'name' => ['type' => 'text',  'col-class' => 'col-lg-12'],
        ],
        'users' => [
            'users' => ['type' => 'manyToMany', 'relation' => 'users', 'column' => 'name',  'col-class' => 'col-lg-12'],
        ]
    ];

    protected $languageFile = 'user::groups';

    protected $routes = [
        'index' => 'settings.groups.index',
        'create' => 'settings.groups.create',
        'show' => 'settings.groups.show',
        'edit' => 'settings.groups.edit',
        'store' => 'settings.groups.store',
        'destroy' => 'settings.groups.destroy',
        'update' => 'settings.groups.update'
    ];

    public function __construct()
    {
        parent::__construct();
    }


    public function afterStore($request, &$entity)
    {

        $loggedUser = \Auth::user();

        if (is_null($request->get('users'))) {
            $entity->users()->sync([]);
        } else {

            $userRepo = \App::make(UserRepository::class);

            $users = $request->get('users');
            foreach ($users as $user) {
                $u = $userRepo->findWithoutFail($user);
                if (empty($u)) {
                    flash(trans($this->languageFile . '.unaproved'))->error();
                    return redirect(route($this->routes['index']));
                }
                if (!$loggedUser->isAdmin() && $u->company_id != $loggedUser->company_id) {
                    flash(trans($this->languageFile . '.unaproved'))->error();
                    return redirect(route($this->routes['index']));
                }
            }

            $entity->users()->sync($users);
        }

    }

    public function afterUpdate($request, &$entity)
    {
        $loggedUser = \Auth::user();

        if (is_null($request->get('users'))) {
            $entity->users()->sync([]);
        } else {
            $userRepo = \App::make(UserRepository::class);

            $users = $request->get('users');
            foreach ($users as $user) {
                $u = $userRepo->findWithoutFail($user);
                if (empty($u)) {
                    flash(trans($this->languageFile . '.unaproved'))->error();
                    return redirect(route($this->routes['index']));
                }
                if (!$loggedUser->isAdmin() && $u->company_id != $loggedUser->company_id) {
                    flash(trans($this->languageFile . '.unaproved'))->error();
                    return redirect(route($this->routes['index']));
                }
            }

            $entity->users()->sync($users);
        }


    }


}
