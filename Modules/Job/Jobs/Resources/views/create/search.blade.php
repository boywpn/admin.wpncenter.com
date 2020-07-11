@extends('layouts.app')

@section('content')

    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">

                <div class="header">
                    <h2>
                        <div class="header-buttons">
                            <a href="{{ route($routes['index']) }}" title="@lang('core::core.crud.back')" class="btn btn-primary btn-back btn-crud">@lang('core::core.crud.back')</a>
                        </div>

                        <div class="header-text">
                            @lang($language_file.'.created_job') - @lang('core::core.crud.details')
                            <small>@lang($language_file.'.module_description')</small>
                        </div>
                    </h2>
                </div>

                <div class="body">
                    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">

                            <form method="POST" action="{{ route('job.jobs.new') }}" class="form-horizontal">
                                @csrf

                                <input type="hidden" value="" name="member_id" id="member_id">
                                <input type="hidden" value="" name="username_id" id="username_id">
                                <input type="hidden" value="" name="username" id="username">
                                <div class="row clearfix">
                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="cust_username">@lang($language_file.'.form.cust_username')</label>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="cust_username" class="form-control" placeholder="@lang($language_file.'.search') @lang($language_file.'.form.cust_username')">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="cust_name">@lang($language_file.'.form.cust_name')</label>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="cust_name" class="form-control" placeholder="@lang($language_file.'.search') @lang($language_file.'.form.cust_name')">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="cust_phone">@lang($language_file.'.form.cust_phone')</label>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="cust_phone" class="form-control" placeholder="@lang($language_file.'.search') @lang($language_file.'.form.cust_phone')">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row clearfix">
                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="cust_phone">@lang($language_file.'.form.user_game')</label>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="user_game" class="form-control" placeholder="@lang($language_file.'.form.user_game')" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="cust_phone">@lang($language_file.'.form.cust_agent')</label>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="cust_agent" class="form-control" placeholder="@lang($language_file.'.form.cust_agent')" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="cust_phone">@lang($language_file.'.form.cust_partner')</label>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="cust_partner" class="form-control" placeholder="@lang($language_file.'.form.cust_partner')" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row clearfix">
                                    <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                                        <button type="submit" name="submit_action" value="deposit" class="btn btn-primary m-t-15 waves-effect"><i class="fa fa-download" aria-hidden="true"></i>  @lang($language_file.'.for_deposit')</button>
                                        <button type="submit" name="submit_action" value="withdraw" class="btn btn-danger m-t-15 waves-effect"><i class="fa fa-upload" aria-hidden="true"></i> @lang($language_file.'.for_withdraw')</button>
                                        <button type="submit" name="submit_action" value="transfer" class="btn btn-warning m-t-15 waves-effect"><i class="fa fa-exchange" aria-hidden="true"></i> @lang($language_file.'.for_transfer')</button>
                                        <button type="submit" name="submit_action" value="promotion" class="btn btn-info m-t-15 waves-effect"><i class="fa fa-download" aria-hidden="true"></i> @lang($language_file.'.for_promotion')</button>
                                    </div>
                                </div>

                            </form>

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
    <link rel="stylesheet" href="/bap/plugins/jquery-ui/jquery-ui.min.css">
    @foreach($cssFiles as $file)
        <link rel="stylesheet" href="{!! Module::asset($moduleName.':css/'.$file) !!}"></link>
    @endforeach
@endpush

@push('scripts')
    <script src="/bap/plugins/jquery-ui/jquery-ui.min.js"></script>
    @foreach($jsFiles as $jsFile)
        <script src="{!! Module::asset($moduleName.':js/'.$jsFile) !!}"></script>
    @endforeach

    <script>
        $( function() {
            $( "#cust_username" ).autocomplete({
                source: function( request, response ) {
                    $.ajax( {
                        url: "{{ route('job.jobs.search.members', 'username') }}",
                        dataType: "json",
                        data: {
                            term: request.term
                        },
                        success: function( data ) {
                            response( data );
                        }
                    } );
                },
                minLength: 2,
                select: function( event, ui ) {
                    $("input#member_id").val(ui.item.member_id);
                    $("input#cust_username").val(ui.item.username);
                    $("input#username_id").val(ui.item.id);
                    $("input#username").val(ui.item.username);
                    $("input#cust_name").val(ui.item.member_name);
                    $("input#cust_phone").val(ui.item.member_phone);

                    $("input#cust_agent").val(ui.item.agent_name);
                    $("input#user_game").val(ui.item.game_name);
                    $("input#cust_partner").val(ui.item.partner_website);
                }
            } );

            $( "#cust_name" ).autocomplete({
                source: function( request, response ) {
                    $.ajax( {
                        url: "{{ route('job.jobs.search.members', 'name') }}",
                        dataType: "json",
                        data: {
                            term: request.term
                        },
                        success: function( data ) {
                            response( data );
                        }
                    } );
                },
                minLength: 2,
                select: function( event, ui ) {
                    $("input#member_id").val(ui.item.member_id);
                    $("input#cust_username").val(ui.item.username);
                    $("input#cust_name").val(ui.item.member_name);
                    $("input#cust_phone").val(ui.item.member_phone);

                    $("input#cust_agent").val(ui.item.agent_name);
                    $("input#user_game").val(ui.item.game_name);
                    $("input#cust_partner").val(ui.item.partner_website);
                }
            } );

            $( "#cust_phone" ).autocomplete({
                source: function( request, response ) {
                    $.ajax( {
                        url: "{{ route('job.jobs.search.members', 'phone') }}",
                        dataType: "json",
                        data: {
                            term: request.term
                        },
                        success: function( data ) {
                            response( data );
                        }
                    } );
                },
                minLength: 2,
                select: function( event, ui ) {
                    $("input#member_id").val(ui.item.member_id);
                    $("input#cust_username").val(ui.item.username);
                    $("input#cust_name").val(ui.item.member_name);
                    $("input#cust_phone").val(ui.item.member_phone);

                    $("input#cust_agent").val(ui.item.agent_name);
                    $("input#user_game").val(ui.item.game_name);
                    $("input#cust_partner").val(ui.item.partner_website);
                }
            } );
        } );
    </script>
@endpush