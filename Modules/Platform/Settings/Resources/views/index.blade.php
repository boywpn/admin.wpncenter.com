@extends('layouts.app')

@section('content')

    <div class="block-header">
        <h2>@lang('settings::settings.module')</h2>
    </div>

    <div class="row">

        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 no-vert-padding">

            @include('settings::partial.menu')

        </div>

        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 no-vert-padding">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">

                    <div class="body">

                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            @widget('Modules\Platform\Settings\Widgets\UserCountWidget',['count_active' =>
                            true,'color'=>'bg-light-green'])
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            @widget('Modules\Platform\Settings\Widgets\UserCountWidget',['count_active' =>
                            false,'widget_title'=>'inactive','color'=>'bg-deep-orange'])
                        </div>

                        @if($company != null )

                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <div class="info-box">
                                    <div class="icon bg-light-blue">
                                        <i class="material-icons ">person</i>
                                    </div>
                                    <div class="content">
                                        <div class="text">@lang('settings::settings.users_limit')</div>
                                        <div class="number">{{ $currentUsers }} / {!! $company->user_limit != null ? $company->user_limit : '&#x221e;' !!} </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <div class="info-box">
                                    <div class="icon bg-purple">
                                        <i class="material-icons ">cloud_upload</i>
                                    </div>
                                    <div class="content">
                                        <div class="text">@lang('settings::settings.storage_limit')</div>
                                        <div class="number">{{ $companyFileSize }} / {!! $company->storage_limit != null ? $company->storage_limit : '&#x221e;' !!} gb </div>
                                    </div>
                                </div>
                            </div>

                        @endif


                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
