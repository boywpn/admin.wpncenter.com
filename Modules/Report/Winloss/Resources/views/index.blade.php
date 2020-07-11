@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        <div class="header-text">
                            @lang($language_file.'.module') {{ (!empty($subFixTitle)) ? " - ".$subFixTitle : "" }}
                            <small>@lang($language_file.'.module_description')</small>
                        </div>
                    </h2>
                </div>
                <div class="body">

                    @yield('custom_body')

                </div>
            </div>
        </div>
    </div>

@endsection