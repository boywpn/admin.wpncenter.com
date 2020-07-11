@extends('layouts.app')

@section('content')


    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card card-account">

                <div class="header">
                    <h2>

                        <div class="header-text">
                            @lang('notifications::notifications.user_notifications')
                            <small>@lang('notifications::notifications.module_description')</small>
                        </div>

                    </h2>


                </div>
                <div class="body">
                            <div id="notification-scroll" data-ui="jscroll-default">
                                <ul class="ul-list-item">
                                    @if($userNotifications->count() == 0)
                                        <li>
                                            <h4 class="text-center">@lang('notifications::notifications.no_new_notifications')</h4>
                                            <p class="text-center big-icon col-blue-grey">
                                                <i class="material-icons big-icon">notifications_none</i>
                                            </p>
                                        </li>
                                    @endif
                                    @foreach($userNotifications as $notification)
                                        @if(isset($notification->data['content']))
                                            <li>

                                                <div class="msg waves-effect waves-block">
                                                    <a title="@lang('notifications::notifications.delete_notification')" data-id="{{$notification->id}}" href="#" class="btn-xs pull-right waves-effect waves-block notification-delete">
                                                        <i class="material-icons col-blue-grey">delete</i>
                                                    </a>
                                                    <a data-id="{{$notification->id}}" href="#" class="btn-xs pull-right waves-effect waves-block notification-mark">
                                                        <i class="material-icons col-blue-grey">fiber_manual_record</i>
                                                    </a>
                                                    <div class="icon-circle {{ $notification->data['color'] }}">
                                                        <i class="material-icons">{{ $notification->data['icon'] }}</i>
                                                    </div>
                                                    <a href="{{ $notification->data['url'] != '' ? $notification->data['url'] : '#' }}" class="info info-90">

                                                        <span style="display: {{ empty($notification->read_at) ? 'block': 'none' }}" class="badge bg-pink pull-right">@lang('core::core.new')</span>

                                                        <h4>{{ $notification->data['content'] }}</h4>
                                                        <p>
                                                            <i class="material-icons">access_time</i> {{ \Modules\Platform\Core\Helper\UserHelper::formatUserDateTime($notification->created_at) }}
                                                        </p>
                                                        @if(isset($notification->data['more_content']))
                                                            <p>{{$notification->data['more_content']}}</p>
                                                        @endif

                                                    </a>
                                                </div>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>

                                <div class="notifications-pagination">
                                    {{ $userNotifications->links() }}
                                </div>

                            </div>

                </div>
            </div>
        </div>
    </div>

@endsection

