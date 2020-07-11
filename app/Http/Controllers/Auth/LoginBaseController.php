<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Modules\Platform\Core\Helper\SettingsHelper;

/**
 * Login Base Controller
 *
 * Class LoginBaseController
 * @package App\Http\Controllers\Auth
 */
class LoginBaseController extends Controller
{
    /**
     * LoginBaseController constructor.
     */
    public function __construct()
    {
        $installed = file_exists(storage_path() . '/installed');

        if (!$installed) {
            \Redirect::to('install')->send();
        }
        
        $defaultLogin = '';
        $defaultPass = '';

        if (config('bap.demo')) {
            $defaultLogin = config('bap.demo_login');
            $defaultPass = config('bap.demo_pass');
        }

        \View::share('defaultLogin', $defaultLogin);
        \View::share('defaultPass', $defaultPass);
    }
}
