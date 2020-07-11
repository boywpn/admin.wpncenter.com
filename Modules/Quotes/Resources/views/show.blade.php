@extends('layouts.app')

@section('content')

    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        <div class="header-buttons">
                            @if(!$disableNextPrev)
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
                            @endif

                                <div class="btn-group btn-crud pull-right">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        @lang('core::core.more') <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        @foreach($actionButtons as $link)
                                            <li>
                                                {{ Html::link($link['href'],$link['label'],$link['attr']) }}
                                            </li>
                                        @endforeach

                                            @if($permissions['destroy'] == '' or Auth::user()->hasPermissionTo($permissions['destroy']))
                                                <li>
                                                    {!! Form::open(['route' => [$routes['destroy'], $entity], 'method' => 'delete']) !!}

                                                    {!! Form::button(trans('core::core.crud.delete'), [ 'type' => 'submit', 'class' => '"btn btn-block btn-link  waves-effect waves-block', 'onclick' => "return confirm($.i18n._('are_you_sure'))" ]) !!}

                                                    {!! Form::close() !!}

                                                </li>
                                            @endif

                                    </ul>
                                </div>



                            <a href="{{ route($routes['index']) }}"
                               class="btn btn-primary waves-effect btn-back btn-crud">@lang('core::core.crud.back')</a>

                            @if($permissions['update'] == '' or Auth::user()->hasPermissionTo($permissions['update']))
                                <a href="{{ route($routes['edit'],$entity) }}"
                                   class="btn btn-primary waves-effect btn-edit btn-crud">@lang('core::core.crud.edit')</a>
                            @endif





                        </div>

                        <div class="header-text">
                            @lang($language_file.'.module') - @lang('core::core.crud.details')
                            <small>@lang($language_file.'.module_description')</small>
                        </div>

                    </h2>

                </div>
                <div class="body">
                    <div class="row">

                        @if($show_fileds_count > 1 || $hasExtensions)

                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <ul class="nav nav-tabs tab-nav-right tabs-left" role="tablist">
                                    <li role="presentation" class="active">
                                        <a href="#tab_details" data-toggle="tab"
                                           title="@lang('core::core.tabs.details')">
                                            @issetbap($baseIcons,'details_icon')
                                                <i class="material-icons">folder</i>
                                            @endissetbap
                                            @issetbap($baseIcons,'details_label')
                                                @lang('core::core.tabs.details')
                                            @endissetbap
                                        </a>
                                    </li>

                                    @foreach($relationTabs as $tabKey => $tab)

                                        @if(Auth::user()->hasPermissionTo($tab['permissions']['browse']))
                                            <li role="presentation">

                                                <a href="#tab_{{$tabKey}}" data-toggle="tab"
                                                   title="@lang($language_file.'.tabs.'.$tabKey)">
                                                    <i class="material-icons">{{$tab['icon']}}</i>
                                                    @lang($language_file.'.tabs.'.$tabKey)
                                                </a>
                                            </li>
                                        @endif

                                    @endforeach

                                    @if($commentableExtension)
                                        <li role="presentation">
                                            <a href="#tab_comments" data-toggle="tab"
                                               title="@lang('core::core.tabs.comments')">
                                                @issetbap($baseIcons,'comments_icon')
                                                    <i class="material-icons">chat</i>
                                                @endissetbap
                                                @issetbap($baseIcons,'comments_label')
                                                    @lang('core::core.tabs.comments')
                                                @endissetbap
                                            </a>
                                        </li>
                                    @endif
                                    @if($attachmentsExtension)
                                        <li role="presentation">
                                            <a href="#tab_attachments" data-toggle="tab"
                                               title="@lang('core::core.tabs.attachments')">
                                                @issetbap($baseIcons,'attachments_icon')
                                                    <i class="material-icons">attach_file</i>
                                                @endissetbap
                                                @issetbap($baseIcons,'attachments_label')
                                                    @lang('core::core.tabs.attachments')
                                                @endissetbap
                                            </a>
                                        </li>
                                    @endif
                                    @if($actityLogDatatable != null )
                                        <li role="presentation">
                                            <a href="#tab_updates" data-toggle="tab"
                                               title="@lang('core::core.tabs.updates')">
                                                @issetbap($baseIcons,'activity_log_icon')
                                                    <i class="material-icons">change_history</i>
                                                @endissetbap
                                                @issetbap($baseIcons,'activity_log_label')
                                                    @lang('core::core.tabs.updates')
                                                @endissetbap
                                            </a>
                                        </li>
                                    @endif
                                </ul>

                            </div>

                        @endif


                        <div class="col-lg-10 col-md-10 col-sm-10">

                            <div class="tab-content">


                                <div role="tabpanel" class="tab-pane active" id="tab_details">

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        @foreach($customShowButtons as $btn)
                                            {!! Html::customButton($btn) !!}
                                        @endforeach


                                    </div>

                                    @foreach($show_fields as $panelName => $panel)
                                        {{ Html::section($language_file,$panelName) }}
                                        @foreach($panel as $fieldName => $options)
                                            @if(!isset($options['in_show_view']) || $options['in_show_view'])
                                                {{
                                                    Html::renderField($entity,$fieldName,$options,$language_file)
                                                }}
                                            @endif

                                        @endforeach

                                    @endforeach

                                    @include('quotes::partial.rows')

                                    @include('core::crud.partial.entity_created_at')

                                </div>

                                @include('core::crud.module.quick_edit')


                                @foreach($relationTabs as $tabKey => $tab)
                                    @if(Auth::user()->hasPermissionTo($tab['permissions']['browse']))
                                        <div role="tabpanel" class="tab-pane" id="tab_{{$tabKey}}">

                                            <div class="related_module_wrapper">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                                        @if($tab['select']['allow'])

                                                            @if(Auth::user()->hasPermissionTo($tab['permissions']['update']))
                                                                <div class="select_relation_wrapper">
                                                                    <a href="#" class="select btn btn-primary waves-effect modal-relation">@lang('core::core.btn.select')</a>

                                                                    <div id="modal_{{$tabKey}}" class="modal fade" role="dialog">
                                                                        <div class="modal-dialog modal-xl">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                    @if(isset($tab['select']['modal_title']))
                                                                                        <h4 class="modal-title">@lang($tab['select']['modal_title'])</h4>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="modal-body linked-records">
                                                                                    <div class="col-lg-12 col-md-12 col-sm-12 linked-records">
                                                                                        @include('core::crud.relation.relation',['datatable' => $tab['newRecordsTable'],'entity'=>$entity,'tab'=>$tab])
                                                                                    </div>

                                                                                </div>
                                                                                <div class="modal-footer">

                                                                                    @include('core::crud.relation.link',['tabkey'=>$tabKey,'entityId' => $entityIdentifier,'route'=>$tab['route']['bind_selected']])

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            @endif
                                                        @endif

                                                        @if($tab['create']['allow'])
                                                            @if(Auth::user()->hasPermissionTo($tab['permissions']['create']))
                                                                <div class="create_new_relation_wrapper">
                                                                    <a href="#" class="select btn btn-primary waves-effect modal-new-relation">@lang('core::core.btn.new')</a>

                                                                    <div data-create-route="{{ route($tab['route']['create'],$tab['create']['post_create_bind']) }}" id="modal_create_{{$tabKey}}"
                                                                         class="modal fade" role="dialog">
                                                                        <div class="modal-dialog modal-lg">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                    @if(isset($tab['create']['modal_title']))
                                                                                        <h4 class="modal-title">@lang($tab['create']['modal_title'])</h4>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="modal-body">

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 linked-records" id="linked_{{$tabKey}}">
                                                        @include('core::crud.relation.relation',['datatable' => $tab['htmlTable'],'entity'=>$entity,'tab'=>$tab])
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach


                                @if($commentableExtension)
                                    <div role="tabpanel" class="tab-pane" id="tab_comments">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            @include('core::extension.comments.list',['entity'=>$entity])
                                        </div>
                                    </div>
                                @endif

                                @if($attachmentsExtension)
                                    <div role="tabpanel" class="tab-pane" id="tab_attachments">

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            @include('core::extension.attachments.list',['entity'=>$entity,'permissions'=>$permissions])
                                        </div>

                                    </div>
                                @endif


                                @if($actityLogDatatable !=  null )
                                    <div role="tabpanel" class="tab-pane" id="tab_updates">

                                        <div class="table-responsive col-lg-12 col-md-12">
                                            @include('core::extension.activity_log.table')
                                        </div>
                                    </div>
                                @endif
                            </div>


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


