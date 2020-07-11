@extends('layouts.app')

@section('content')

    @if($settingsMode)
        <div class="block-header">
            <h2>@lang('settings::settings.module')</h2>
        </div>
    @endif

    @if(!$disableWidgets)
        @includeIf($moduleName.'::index-top')
    @endif

    <div class="row">

            @if($settingsMode)
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 no-vert-padding" >
                    @include('settings::partial.menu')
                </div>
            @endif

            @if($settingsMode)
                    <div  class="col-lg-9 col-md-9 col-sm-9 col-xs-12 no-vert-padding">
            @endif



            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>

                            <div class="header-buttons">
                                @if($settingsPermission != '' && Auth::user()->hasPermissionTo($settingsPermission))
                                    @if(count($moduleSettingsLinks) > 0 )
                                    <div class="btn-group btn-crud pull-right">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            @lang('core::core.settings') <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @foreach($moduleSettingsLinks as $link)
                                             <li>
                                                 <a href="{{ route($link['route']) }}">{{ trans($language_file.'.'.$link['label']) }}</a>
                                             </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                @endif

                                @if(!empty($indexActionButtons))
                                    <div class="btn-group btn-crud pull-right">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            @lang('core::core.more') <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @foreach($indexActionButtons as $link)
                                                <li>
                                                    {{ Html::link($link['href'],$link['label'],$link['attr']) }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @foreach($moreButtonLink as $link)
                                    <a href="{{ route($link['route']) }}" title="{{ trans($language_file.'.'.$link['label']) }}" class="btn btn-{{ $link['color'] }} btn-crud">{{ trans($language_file.'.'.$link['label']) }}</a>
                                @endforeach

                                @if($permissions['create'] == '' or Auth::user()->hasPermissionTo($permissions['create']))
                                    <a href="{{ route($routes['create']) }}" title="@lang('core::core.crud.create')" class="btn btn-primary btn-create btn-crud">@lang('core::core.crud.create')</a>
                                @endif

                                @include('core::extension.advanced_view.advanced_views',['dataTable' => $dataTable,'hasQueryFilters' => $dataTable->hasQueryFilters,'advancedViewsEnabled' => $advancedViewsEnabled])

                                @if($settingsBackRoute != '')
                                    <a href="{{ route($settingsBackRoute) }}" title="@lang('core::core.crud.back')" class="btn btn-default btn-crud">@lang('core::core.crud.back')</a>
                                @endif
                            </div>
                            <div class="header-text">



                                @lang($language_file.'.module')  - @lang('core::core.crud.list')
                                <small>@lang($language_file.'.module_description')</small>
                            </div>

                        </h2>
                    </div>
                    <div class="body">


                        @if($dataTable->hasQueryFilters)
                            @include('core::crud.partial.query_filter_builder_in_index')
                        @endif

                        <div class="table-responsive  col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            {!! $dataTable->table(['width' => '100%']) !!}
                        </div>

                        <div data-create-route="{{ route($routes['create'],['mode'=>'modal']) }}" id="modal_quick_create" class="modal fade" role="dialog">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                        <h4 class="modal-title">@lang($language_file.'.module') - @lang('core::core.crud.create')</h4>

                                    </div>
                                    <div class="modal-body">

                                    </div>
                                </div>
                            </div>
                        </div>

                        @include('core::crud.partial.csv_import')
                        @include('core::crud.module.quick_edit')

                    </div>
                </div>


            </div>



            @if($settingsMode)
                    </div>
            @endif
    </div>


    @if(!$disableWidgets)
        @includeIf($moduleName.'::index-bottom')
    @endif

    @include('core::extension.advanced_view.advanced_view_modal',
        ['dataTable' => $dataTable,
         'hasQueryFilters' => $dataTable->hasQueryFilters,
         'advancedViewsEnabled' => $advancedViewsEnabled,
         'availableColumns' => $availableColumns
         ])




@endsection

@push('css')
    @foreach($cssFiles as $file)
        <link rel="stylesheet" href="{!! Module::asset($moduleName.':css/'.$file) !!}"></link>
    @endforeach
@endpush

@push('scripts')
    @foreach($jsFiles as $jsFile)
        <script src="{!! Module::asset($moduleName.':js/'.$jsFile) !!}"></script>
    @endforeach
@endpush

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush


