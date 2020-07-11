@extends('layouts.app')

@section('content')

    <div class="block-header">
        <h2>@lang('settings::settings.module')</h2>
    </div>

    <div class="row">

        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 no-vert-padding" >

            @include('settings::partial.menu')

        </div>

        <div  class="col-lg-9 col-md-9 col-sm-9 col-xs-12 no-vert-padding">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                        <div class="header">
                            <h2>
                                @lang('settings::display.module')
                                <small>@lang('settings::display.module_description')</small>
                            </h2>
                        </div>
                        <div class="body">


                            {!! form($display_settings_form) !!}

                        </div>
                    </div>
                </div>
            </div>
    </div>

@endsection

@push('scripts')
{!! JsValidator::formRequest(\Modules\Platform\Settings\Http\Requests\SaveDisplaySettingsRequest::class, '#display_settings_form') !!}

<script src="{!! Module::asset('settings:js/BAP_DisplaySettings.js') !!}"></script>
@endpush
