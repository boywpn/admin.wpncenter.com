<?php

use Modules\Platform\Core\Helper\SettingsHelper as SettingsHelper;
use Krucas\Settings\Facades\Settings as Settings;

?>

@extends('layouts.auth')

@section('body_class','login-page')

@section('content')

    <div class="login-box">


        <div class="logo">

            <a href="javascript:void(0);"><img src="{{ asset('bap/logo/multicrm_logo.png') }}" /></a>

        </div>
        <div class="card">
            <div class="body">
                <form id="sign_up" method="POST" action="{{ route('login') }}">

                    @if (isset($errorMessage))
                        <span class="help-block">
                                <strong>{{ $errorMessage }}</strong>
                        </span>
                    @endif

                    {{ csrf_field() }}

                    <div class="msg">
                        @lang('auth.login_title')

                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line {{ $errors->has('email') ? ' error' : '' }}">
                            <input id="name" type="text" placeholder="@lang('auth.username')"
                                   value="{{ $defaultLogin }}" class="form-control" name="email" autofocus>
                        </div>

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                             </span>
                        @endif

                    </div>

                    <div class="input-group {{ $errors->has('password') ? ' error' : '' }}">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input id="password" placeholder="@lang('auth.password')" value="{{ $defaultPass }}"
                                   type="password" class="form-control" name="password">
                        </div>
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                             </span>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-xs-8 p-t-5">
                            <input type="checkbox" id="rememberme" name="remember"
                                   {{ old('remember') ? 'checked' : '' }} class="filled-in chk-col-pink">
                            <label for="rememberme">@lang('auth.remember_me')</label>
                        </div>
                        <div class="col-xs-12">
                            <button class="btn btn-block bg-pink waves-effect"
                                    type="submit">@lang('auth.sign_in')</button>
                        </div>


                    </div>
                    <div class="row m-t-15 m-b--20">
                        @if(config('bap.allow_registration'))
                            <div class="col-xs-6">
                                <a href="{{ route('register') }}">@lang('auth.regiser')</a>
                            </div>
                            <div class="col-xs-6 align-right">
                                <a href="{{ route('password.request') }}">@lang('auth.forget_password')</a>
                            </div>
                        @else
                            @if(config('bap.demo'))
                            <div class="col-xs-6">
                                <div class="dropdown">
                                    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        Choose User
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                        <li><a id="userAdmin" href="#">Admin</a></li>
                                        <li><a id="userCompany1" href="#">OSCORP 1 Manager</a></li>
                                        <li><a id="userCompany2" href="#">Umbrella 2 Manager</a></li>
                                    </ul>
                                </div>
                            </div>
                                <div class="col-xs-6 align-right">
                                    <a href="{{ route('password.request') }}">@lang('auth.forget_password')</a>
                                </div>
                            @else
                                <div class="col-xs-12 align-right">
                                    <a href="{{ route('password.request') }}">@lang('auth.forget_password')</a>
                                </div>
                                @endif


                        @endif

                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection
