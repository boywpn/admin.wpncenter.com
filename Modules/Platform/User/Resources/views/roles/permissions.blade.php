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
                    <div class="header">
                        <h2>

                            <a href="{{ route('settings.roles.show',$role->id) }}" class="btn btn-primary btn-create btn-crud">@lang('user::roles.back')</a>

                            @lang('user::roles.assign_permissions_to') - {{ $role->display_name }}
                            <small>@lang('user::roles.module_permissions_description')</small>
                        </h2>
                    </div>
                    <div class="body">

                        <div class="row clearfix">
                            <div class="col-sm-12">

                                {{ Form::open(['route'=>['settings.roles.permissions-save',$role->id],'method'=>'POST']) }}

                                @foreach($permissions as $group => $permission )
                                    <div class="col-sm-6">
                                        <h2 class="card-inside-title">{{ $group }}</h2>
                                        @foreach($permission as $key => $perm)
                                            <div class="switch">
                                                <label>
                                                    {{ Form::checkbox('permissions[]',strtolower($perm['name']),in_array(strtolower($perm['name']),$rolePermissions)),['id' => strtolower($perm['name'])] }}
                                                    <span class="lever switch-col-red">
                                                    </span>
                                                    {{ $perm['name'] }}
                                                </label>
                                            </div>
                                        @endforeach

                                    </div>
                                @endforeach

                                <div class="col-sm-12">


                                    {!! Form::submit(trans('user::roles.save_permissions'),['class="btn btn-primary m-t-15 waves-effect"']) !!}
                                </div>

                                {!! Form::close() !!}

                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    {!! JsValidator::formRequest(\Modules\Platform\Settings\Http\Requests\SaveCompanySettingsRequest::class, '#company_settings_form') !!}
@endpush