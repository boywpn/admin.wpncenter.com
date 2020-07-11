<?php

namespace Modules\Platform\User\Http\Controllers\User;

use Modules\Platform\Core\Datatable\Scope\UserActivityScope;
use Modules\Platform\Core\Http\Controllers\AppBaseController;
use Modules\Platform\User\Datatables\UserActivityDatatable;
use Modules\Platform\User\Http\Requests\AccountUpdateRequest;
use Modules\Platform\User\Http\Requests\UserRequest;
use Modules\Platform\User\Repositories\UserRepository;

/**
 * Class UserActivityController
 * @package Modules\Platform\User\Http\Controllers\User
 */
class UserActivityController extends AppBaseController
{
    /**
     * @var UserRepository
     */
    private $repo;

    /**
     * UserActivityController constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        parent::__construct();

        $this->repo = $repository;
    }

    /**
     * @param $identifier
     * @param UserActivityDatatable $dataTable
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     */
    public function activity($identifier, UserActivityDatatable $dataTable)
    {
        $entity = $this->repo->findWithoutFail($identifier);

        if (!\Auth::user()->hasPermissionTo('settings.access')) {
            if ($entity->company_id != \Auth::user()->company_id) {
                flash(trans('user::users.unaproved'))->error();
                return redirect(route('settings.users.index'));
            }
        }

        if (empty($entity)) {
            flash(trans('user::users.user_not_found'))->error();
            return redirect(route('settings.users.index'));
        }

        $dataTable->addScope(new UserActivityScope($entity->id));

        return $dataTable->render('user::users.activity', [
            'entity' => $entity
        ]);
    }
}
