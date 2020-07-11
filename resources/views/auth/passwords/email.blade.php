@extends('layouts.auth')

@section('body_class','fp-page')

@section('content')

    <div class="fp-box login-box">

        <div class="logo">

            <a href="javascript:void(0);"><img src="{{ asset('bap/logo/multicrm_logo.png') }}" /></a>

        </div>
        <div class="card">
            <div class="body">
                <form id="reset_password" method="POST" action="{{ route('password.email') }}">

                    {{ csrf_field() }}

                    <div class="msg">

                        @lang('auth.reset_password_title')

                    </div>

                    @if(session()->get('status') != null )
                        <div class="alert alert-success">
                            {{ session()->get('status') }}
                        </div>
                    @endif

                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">email</i>
                        </span>

                        <div class="form-line {{ $errors->has('email') ? ' error' : '' }}">
                            <input id="name" type="text" placeholder="@lang('auth.email')" class="form-control" name="email" value="{{ old('email') }}" autofocus>
                        </div>

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                             </span>
                        @endif
                    </div>

                    <button class="btn btn-block btn-lg bg-pink waves-effect" type="submit">@lang('auth.reset_my_password')</button>

                    <div class="row m-t-20 m-b--5 align-center">
                        <a href="{{ route('login') }}">@lang('auth.sign_in_small_cap')</a>
                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection


