<?php

namespace Modules\Platform\Settings\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Krucas\Settings\Facades\Settings;
use Modules\Platform\Core\Http\Controllers\AppBaseController;
use Modules\Platform\Core\Repositories\AttachmentsRepository;
use Modules\Platform\User\Repositories\UserRepository;

class SettingsController extends AppBaseController
{

    private $attachmentRepo;

    private $userRepo;

    public function __construct(AttachmentsRepository $repository, UserRepository $userRepository)
    {
        parent::__construct();

        $this->attachmentRepo = $repository;
        $this->userRepo       = $userRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $user = \Auth::user();
        $company = $user->company;

        if($company == null ){
            $company = session()->get('currentCompany');
        }

        return view('settings::index')
            ->with('company',$company)
            ->with('companyFileSize',$this->attachmentRepo->countFileSizeForCompanyFormatted($company))
            ->with('currentUsers',   $this->userRepo->countUsersForCompany($company));
    }

    protected function clearCache()
    {
        Artisan::call('cache:clear');

        flash(trans('settings::settings.menu.clear_cache_completed'))->success();
        return redirect()->route('settings.index');
    }

    protected function optimize()
    {

        Artisan::call('config:cache');
        Artisan::call('config:clear');
        Artisan::call('route:cache');
        Artisan::call('route:clear');
        Artisan::call('cache:clear');

        return redirect()->route('settings.optimize_return');
    }

    protected function optimizeReturn()
    {
        flash(trans('settings::settings.menu.optimize_completed'))->success();
        return redirect()->route('settings.index');
    }
}
