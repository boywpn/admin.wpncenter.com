@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-4">

            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">

                        <div class="header">
                            <h2>
                                <div class="header-buttons">
                                    {{--<a href="{{ route($routes['index']) }}" title="@lang('core::core.crud.back')" class="btn btn-primary btn-back btn-crud">@lang('core::core.crud.back')</a>--}}
                                    <a onclick="getTable()" type="button" class="btn bg-brown btn-crud waves-effect">
                                        <i class="material-icons">access_alarm</i>
                                        <span>@lang($language_file.'.reload_table')</span>
                                    </a>
                                </div>

                                <div class="header-text">
                                    @lang($language_file.'.deposit.title')
                                    <small>@lang($language_file.'.module_description')</small>
                                </div>
                            </h2>
                        </div>

                        <div class="body">

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">

                                    <table id="job-new" class="table table-hover">
                                        <thead>
                                        <tr class="">
                                            <th>@lang($language_file.'.time')</th>
                                            <th>@lang($language_file.'.partner')</th>
                                            <th>@lang($language_file.'.transfer_to')</th>
                                            <th>@lang($language_file.'.amount')</th>
                                            <th width="2%">@lang($language_file.'.action')</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>

                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">

                        <div class="header">
                            <h2>
                                <div class="header-buttons">
                                </div>

                                <div class="header-text">
                                    @lang($language_file.'.deposit.job_processing')
                                    <small>@lang($language_file.'.module_description')</small>
                                </div>
                            </h2>
                        </div>

                        <div class="body">

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">

                                    <table id="job-processing" class="table table-hover">
                                        <thead>
                                            <tr class="">
                                                <th>@lang($language_file.'.time')</th>
                                                <th>@lang($language_file.'.partner')</th>
                                                <th>@lang($language_file.'.transfer_to')</th>
                                                <th>@lang($language_file.'.amount')</th>
                                                <th width="2%">@lang($language_file.'.action')</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>

                                </div>
                            </div>

                        </div>

                    </div>
                </div>

            </div>

        </div>

        <div class="col-md-8">

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">

                        <div class="header">
                            <h2>
                                <div class="header-buttons">

                                </div>

                                <div class="header-text">
                                    @lang($language_file.'.deposit.detail-job')
                                    <small>@lang($language_file.'.module_description')</small>
                                </div>
                            </h2>
                        </div>

                        <div class="body" id="job-detail">

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
    <!-- Light Gallery Plugin Css -->
    <link href="/bap/plugins/light-gallery/css/lightgallery.css" rel="stylesheet">
    @foreach($cssFiles as $file)
        <link rel="stylesheet" href="{!! Module::asset($moduleName.':css/'.$file) !!}"></link>
    @endforeach
@endpush

@push('scripts')
    <script src="/bap/plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Light Gallery Plugin Js -->
    <script src="/bap/plugins/light-gallery/js/lightgallery-all.js"></script>
    @foreach($jsFiles as $jsFile)
        <script src="{!! Module::asset($moduleName.':js/'.$jsFile) !!}"></script>
    @endforeach

    <script>

        $('document').ready(function(){
            getTable();
        })

        function getTable(){
            $.ajax( {
                url: "{{ route('job.jobs.list-table', '1') }}",
                dataType: "json",
                success: function( data ) {

                    $('table#job-new tbody').html(data.new);
                    $('table#job-processing tbody').html(data.processing);

                }
            } );
        }

        function viewJob(id){
            $.ajax( {
                url: "/job/jobs/view-job/" + id,
                dataType: "html",
                success: function( html ) {

                    $("#job-detail").html(html)

                    $('#aniimated-thumbnials').lightGallery({
                        thumbnail: true,
                        selector: 'a'
                    });

                }
            } );
        }
    </script>
@endpush