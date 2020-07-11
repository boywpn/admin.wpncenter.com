@extends('layouts.app')

@section('content')

    <div class="block-header">
        <h2>@lang('account::account.module') - {{ Auth::user()->name }}</h2>
    </div>

    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card card-account">


                <div class="body">

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <ul class="nav nav-tabs tab-nav-right" role="tablist">

                                <li role="presentation" class="active">
                                    <a href="#preferences"
                                       data-toggle="tab">@lang('account::account.menu.preferences')</a>
                                </li>

                                <li role="presentation">
                                    <a href="#password"
                                       data-toggle="tab">@lang('account::account.menu.change_password')</a>
                                </li>

                            </ul>
                            <div class="tab-content">

                                <div role="tabpanel" class="tab-pane fade in active" id="preferences">

                                    <div class="in-account">

                                        @include('account::partial.update-account')
                                    </div>
                                </div>

                                <div role="tabpanel" class="tab-pane fade" id="password">

                                    <div class="in-account">
                                        @include('account::partial.change-password')
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush
