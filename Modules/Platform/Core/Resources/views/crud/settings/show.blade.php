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

                            <div class="btn-group next-prev-btn-group" role="group">
                                @if($prev_record)
                                    <a href="{{ route($routes['show'],$prev_record) }}"
                                       title="@lang('core::core.crud.prev')"
                                       class="btn btn-primary waves-effect btn-crud btn-prev">@lang('core::core.crud.prev')</a>
                                @endif

                                @if($next_record)
                                    <a href="{{ route($routes['show'],$next_record) }}"
                                       title="@lang('core::core.crud.next')"
                                       class="btn btn-primary waves-effect btn-crud btn-next">@lang('core::core.crud.next')</a>
                                @endif
                            </div>

                            <a href="{{ route($routes['index']) }}" title="@lang('core::core.crud.back')"
                               class="btn btn-primary waves-effect btn-back btn-crud">@lang('core::core.crud.back')</a>

                            <a href="{{ route($routes['edit'],$entity) }}"
                               class="btn btn-primary waves-effect btn-edit btn-crud">@lang($language_file.'.edit')</a>

                            {!! Form::open(['route' => [$routes['destroy'], $entity], 'method' => 'delete']) !!}

                            {!! Form::button(trans('core::core.crud.delete'), [ 'type' => 'submit', 'class' => 'btn btn-danger waves-effect btn-edit btn-crud', 'onclick' => "return confirm($.i18n._('are_you_sure'))" ]) !!}

                            {!! Form::close() !!}



                            @lang($language_file.'.module') - @lang($language_file.'.details')
                            <small>@lang($language_file.'.module_description')</small>

                        </h2>

                    </div>
                    <div class="body">
                        <div class="row">

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                @foreach($customShowButtons as $btn)
                                    {!! Html::customButton($btn) !!}
                                @endforeach
                            </div>


                            @foreach($show_fields as $panelName => $panel)
                                {{ Html::section($language_file,$panelName) }}


                                    @foreach($panel as $fieldName => $options)
                                        @fieldPermission($options)
                                        {{
                                            Html::renderField($entity,$fieldName,$options,$language_file)
                                        }}
                                        @endfieldPermission
                                    @endforeach

                            @endforeach

                        </div>

                    </div>
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


