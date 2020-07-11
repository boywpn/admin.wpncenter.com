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
                            @lang('announcement::announcement.module')
                            <small>@lang('announcement::announcement.module_description')</small>
                        </h2>
                    </div>
                    <div class="body">


                        <div class="row clearfix">

                            <div class="col-sm-12">

                                {!! form($announcement_form) !!}

                            </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
{!! JsValidator::formRequest(\Modules\Platform\Announcement\Http\Requests\SaveAnnouncementRequest::class, '#announcement_settings_form') !!}
@endpush

@push('scripts')
<script type="text/javascript" src="{{ asset('/bap/plugins/ckeditor/ckeditor.js')}}"></script>
@endpush