@extends('layouts.app')

@section('content')


    <div class="row">

        <div id="calendar-container" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>

                        <div class="header-buttons">
                            <div class="btn-group btn-crud pull-right">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                    @lang('core::core.settings') <span class="caret"></span>
                                </button>

                                <ul class="dropdown-menu">

                                    <li>
                                        <a id="create-new-calendar" href="#" data-create-route="{{ route('calendar.calendars.create',['mode'=>'modal','afterAction' => 'refreshCalendarList']) }}">@lang('calendar::calendar.create_calendar')</a>
                                    </li>
                                    <li class="divider"></li>
                                    <li class="dropdown-header">@lang('core::core.settings')</li>
                                    <li>
                                        <a href="{{ route('calendar.calendars.edit',$userCalendar->id) }}">@lang('calendar::calendar.calendar_settings')</a>
                                    </li>
                                    @can('calendar.settings')
                                        <li>
                                            <a href="{{ route('calendar.status.index') }}">@lang('calendar::calendar.event_status')</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('calendar.priority.index') }}">@lang('calendar::calendar.event_priority')</a>
                                        </li>
                                    @endcan
                                </ul>
                            </div>

                            <div class="btn-group btn-crud pull-right width-200">
                                {{ Form::select('calendar', $accessibleCalendars,$userCalendar->id,['class' => 'select2','id'=>'switch-calendar']) }}
                            </div>

                            @can('event.create')
                                <a data-create-route="{{ route('calendar.events.create',['mode'=>'modal','calendar_id' => $userCalendar->id,'afterAction' => 'refreshCalendar']) }}" class="btn btn-primary btn-create btn-crud" id="create-calendar-event"
                                   href="#">@lang('calendar::calendar.create_event')</a>

                            @endcan

                            <a  class="btn btn-default btn-create btn-crud btn-circle font-19" id="calendar-full-screen" href="#">
                                <i class="material-icons font-14">zoom_out_map</i>
                            </a>

                            </div>

                        <div class="header-text">
                            {{ $userCalendar->name }}

                            <small>@lang('calendar::calendar.module_description')</small>

                        </div>
                    </h2>


                </div>

                <div class="body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            {!! $fullCalendar->generate() !!}
                        </div>
                    </div>
                </div>
            </div>
        @endsection

@push('css-up')

   <link href="{{ asset('css/fullcalendar.print.css') }}" rel="stylesheet" media="print">
   <link href="{{ asset('css/fullcalendar.css') }}" rel="stylesheet">

@endpush
@push('scripts')

                <div class="modal fade" id="calendar-event-modal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">

                            <div class="modal-body modal-card">

                            </div>
                        </div>
                    </div>
                </div>

                <script src="{{ asset('js/fullcalendar.js') }}"></script>
                <script src="{{ asset('js/locale-all.js') }}"></script>

                @if(config('fullcalendar.enable_gcal'))
                    <script src="{{ asset('js/gcal.js') }}"></script>
                @endif

                <script src="{!! Module::asset('calendar:js/BAP_Calendar.js') !!}"></script>


    @endpush

