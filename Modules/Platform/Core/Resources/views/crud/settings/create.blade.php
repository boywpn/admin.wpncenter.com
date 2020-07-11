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

                            <div class="header-buttons">
                                <a href="{{ route($routes['index']) }}" class="btn btn-primary btn-back btn-crud">@lang('core::core.crud.back')</a>
                            </div>
                            <div class="header-text">
                                @lang($language_file.'.module') - @lang('core::core.crud.create')
                                <small>@lang($language_file.'.module_description')</small>
                            </div>

                        </h2>


                    </div>
                    <div class="body">

                        <div class="row">

                            {!! form_start($form) !!}

                            @foreach($show_fields as $panelName => $panel)

                                {{ Html::section($language_file,$panelName) }}


                                @foreach($panel as $fieldName => $options)

                                    @fieldPermission($options)
                                    @if(!isset($options['hide_in_form']))
                                        @if($loop->iteration % 2 == 0)
                                            <div class="{{ isset($options['col-class']) ? $options['col-class'] : 'col-lg-6 col-md-6 col-sm-6 col-xs-6' }}">
                                                @else
                                                    <div class="{{ isset($options['col-class']) ? $options['col-class'] : 'col-lg-6 col-md-6 col-sm-6 col-xs-6 clear-left' }}">
                                                        @endif

                                                        {!! form_row($form->{$fieldName}) !!}
                                                    </div>
                                                @endif
                                   @endfieldPermission($options)

                                                @endforeach

                                                @endforeach



                                                {!! form_end($form, $renderRest = true) !!}

                                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @foreach($includeViews as $v)
                @include($v)
            @endforeach


            @endsection

            @push('scripts')
            @foreach($jsFiles as $jsFile)
                <script src="{!! Module::asset($moduleName.':js/'.$jsFile) !!}"></script>
    @endforeach
    @endpush

    @if($form_request != null )
        @push('scripts')
        {!! JsValidator::formRequest($form_request, '#module_form') !!}
        @endpush
    @endif
