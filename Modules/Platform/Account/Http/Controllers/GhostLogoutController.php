<?php

namespace Modules\Platform\Account\Http\Controllers;

use Modules\Platform\Core\Http\Controllers\AppBaseController;
use Modules\Platform\User\Repositories\UserRepository;

/**
 *
 * Logout and login as original user
 *
 * Class GhostLogoutController
 * @package Modules\Platform\Account\Http\Controllers
 */
class GhostLogoutController extends AppBaseController
{
    /**
     * @var UserRepository
     */
    private $userRepo;

    /**
     * GhostLogoutController constructor.
     * @param UserRepository $repo
     */
    public function __construct(UserRepository $repo)
    {
        parent::__construct();

        $this->userRepo = $repo;
    }


    /**
     * Logout from ghost account
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        $identifier = \Session::pull('original_user');

        $user = $this->userRepo->findWithoutFail($identifier);

        if (empty($user)) {
            flash(trans('user::users.user_not_found'))->error();
            return redirect('/home');
        }

        \Auth::login($user);

        flash(trans('user::users.logged_as', ['full_name' => $user->name]))->warning();

        return redirect(route('settings.users.show', $identifier));
    }
}
