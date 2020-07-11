@extends('layouts.auth')

@section('body_class','signup-page')

@section('content')

    <div class="signup-box login-box">

        <div class="logo">

            <a href="javascript:void(0);"><img src="{{ asset('bap/logo/multicrm_logo.png') }}" /></a>

        </div>
        <div class="card">
            <div class="body">
                <form id="sign_up" method="POST" action="{{ route('register') }}">

                    {{ csrf_field() }}

                    <div class="msg">@lang('auth.register_title')</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line {{ $errors->has('first_name') ? ' error' : '' }}">
                            <input id="name" placeholder="@lang('auth.first_name')" type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" autofocus>
                        </div>

                        @if ($errors->has('first_name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('first_name') }}</strong>
                             </span>
                        @endif

                    </div>
                    <div class="input-group {{ $errors->has('email') ? ' error' : '' }}">
                        <span class="input-group-addon">
                            <i class="material-icons">email</i>
                        </span>
                        <div class="form-line">
                            <input id="email" placeholder="@lang('auth.email')" type="email" class="form-control" name="email" value="{{ old('email') }}">
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
                            <input id="password" placeholder="@lang('auth.password')" type="password" class="form-control" name="password">
                        </div>
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                             </span>
                        @endif
                    </div>
                    <div class="input-group {{ $errors->has('password_confirmation') ? ' error' : '' }}">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input id="password-confirm" placeholder="@lang('auth.confirm_password')" type="password" class="form-control" name="password_confirmation">
                        </div>

                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                             </span>
                        @endif
                    </div>

                    <button class="btn btn-block btn-lg bg-pink waves-effect" type="submit">@lang('auth.sign_up')</button>

                    <div class="m-t-25 m-b--5 align-center">
                        <a href="{{ route('login') }}">@lang('auth.already_have_membership')</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
