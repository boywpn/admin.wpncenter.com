<?php

namespace Modules\Platform\User\Http\Controllers\User;

use HipsterJazzbo\Landlord\Facades\Landlord;
use Modules\Platform\Companies\Repositories\CompanyRepository;
use Modules\Platform\Companies\Service\CompanyService;
use Modules\Platform\Core\Http\Controllers\AppBaseController;
use Modules\Platform\User\Repositories\UserRepository;

/**
 * Ghost login as selected user
 * Class UserGhostLoginController
 * @package Modules\Platform\User\Http\Controllers
 */
class UserGhostLoginController extends AppBaseController
{

    /**
     * @var UserRepository
     */
    private $userRepo;

    /**
     * @var CompanyService
     */
    private $companyService;

    /**
     * UserGhostLoginController constructor.
     * @param UserRepository $repository
     * @param CompanyService $companyService
     */
    public function __construct(UserRepository $repository, CompanyService $companyService)
    {
        parent::__construct();
        $this->userRepo = $repository;
        $this->companyService = $companyService;
    }

    /**
     * Ghost login as user
     *
     * @param $identifer
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function login($identifer)
    {
        $user = $this->userRepo->findWithoutFail($identifer);

        if (!\Auth::user()->hasPermissionTo('settings.access')) {
            if ($user->company_id != \Auth::user()->company_id) {
                flash(trans('user::users.unaproved'))->error();
                return redirect(route('settings.users.index'));
            }
        }

        if (empty($user)) {
            flash(trans('user::users.user_not_found'))->error();

            return redirect()->back();
        }

        \Session::put('original_user', \Auth::user()->id);

        // Switch context to user company
        if(!empty($user->company)) {
            Landlord::addTenant('company_id', $user->company->id);
            $this->companyService->switchContext($user->company->id);
        }

        \Auth::login($user);

        flash(trans('user::users.logged_as', ['full_name' => $user->name]))->warning();

        return redirect('/');
    }
}
