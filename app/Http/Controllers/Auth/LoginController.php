<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends LoginBaseController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('guest')->except('logout');
    }


    public function toLogin(){
        return redirect()->to('/login');
    }



    /**
     * Check is user is activated on login
     *
     * @param Request $request
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|exists:users,' . $this->username() . ',is_active,1',
            'password' => 'required',
        ], [
            $this->username() . '.exists' => trans('core::core.invalid_login')
        ]);
    }


    /**
     * Register Logged In Activity
     *
     * @param Request $request
     * @param $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticated(Request $request, $user)
    {
        activity()
            ->inLog('login-logout')
            ->performedOn($user)->log('LOGGED_IN');
    }

    /**
     * Regiser logged Out
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request)
    {
        activity()
            ->inLog('login-logout')
            ->performedOn(\Auth::user())->log('LOGGED_OUT');

        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/');
    }
}
