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

                            <a href="{{ route('settings.menu_manager.create_element') }}" class="btn bg-pink float-right m-l-5">@lang('menumanager::menu_manager.add')</a>


                            @lang('menumanager::menu_manager.module')
                            <small>@lang('menumanager::menu_manager.module_description')</small>
                        </h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-sm-12 col-xs-12">
                                <b><i class="fa fa-info-circle" aria-hidden="true"></i> @lang('menumanager::menu_manager.help')</b> <br />
                                @lang('menumanager::menu_manager.additional_description') <br /><br />

                                <a target="_blank" href="/bap/pages/ui/icons.html"> @lang('menumanager::menu_manager.icon_ref')</a>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12">

                                <h2 class="card-inside-title">@lang('menumanager::menu_manager.main_menu')</h2>



                                <div class="dd nestable-with-handle">
                                    <ol class="dd-list">
                                        @foreach($mainMenu as $menu)
                                            @if($menu->parent_id == null )
                                                @include('menumanager::partial.menuItem',['menu'=>$menu])
                                            @endif
                                        @endforeach
                                    </ol>
                                </div>

                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                {!! form($menuForm) !!}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('css')
    <link href="{{ asset('bap/plugins/nestable/jquery-nestable.css') }}" rel="stylesheet" type="text/css">
@endpush
@push('scripts')
    <script src="{{ asset('bap/plugins/nestable/jquery.nestable.js')}}"></script>
    <script src="{!! Module::asset('menumanager:js/BAP_MenuManager.js') !!}"></script>

    {!! JsValidator::formRequest(\Modules\Platform\MenuManager\Http\Requests\SaveMenuElementRequest::class, '#save_menu_element_form') !!}
@endpush
