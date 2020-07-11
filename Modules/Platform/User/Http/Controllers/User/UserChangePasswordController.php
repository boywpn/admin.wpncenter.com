<?php

namespace Modules\Platform\User\Http\Controllers\User;

use Modules\Platform\Core\Http\Controllers\AppBaseController;
use Modules\Platform\User\Http\Requests\AccountChangePasswordRequest;
use Modules\Platform\User\Http\Requests\UserChangePasswordRequest;
use Modules\Platform\User\Repositories\UserRepository;

/**
 * Class UserChangePasswordController
 * @package Modules\Platform\User\Http\Controllers\User
 */
class UserChangePasswordController extends AppBaseController
{

    /**
     * @var UserRepository
     */
    private $userRepo;

    /**
     * UserGhostLoginController constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        parent::__construct();
        $this->userRepo = $repository;
    }

    /**
     * @param $identfier
     * @param UserChangePasswordRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function changePassword($identfier, UserChangePasswordRequest $request)
    {
        if (config('bap.demo')) {
            flash(trans('core::core.you_cant_do_that_its_demo'))->error();
            return redirect()->back();
        }

        $user = $this->userRepo->findWithoutFail($identfier);

        if (!\Auth::user()->hasPermissionTo('settings.access')) {
            if ($user->company_id != \Auth::user()->company_id) {
                flash(trans('user::users.unaproved'))->error();
                return redirect(route('settings.users.index'));
            }
        }

        if (empty($user)) {
            flash(trans('user::users.user_not_found'))->error();

            return redirect(route('settings.users.index'));
        }

        $this->userRepo->update([
            'password' => bcrypt($request->get('password'))
        ], $identfier);

        flash(trans('user::users.updated'))->success();

        return redirect()->back();
    }
}
