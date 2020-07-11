@if(\Auth::user()->unreadNotifications()->orderBy('id','desc')->take(10)->count() > 0 )
    @foreach(\Auth::user()->unreadNotifications()->orderBy('id','desc')->take(10)->get() as $notification)
        @if(isset($notification->data['content']))
            <li>
                <a href="{{ $notification->data['url'] != '' ? $notification->data['url'] : '#' }}" class=" waves-effect waves-block">
                    <div class="icon-circle {{ $notification->data['color'] }}">
                        <i class="material-icons">{{ $notification->data['icon'] }}</i>
                    </div>
                    <div class="menu-info filled">
                        <h4>{{ $notification->data['content'] }}</h4>
                        <p>
                            <i class="material-icons">access_time</i> {{ \Modules\Platform\Core\Helper\UserHelper::formatUserDateTime($notification->created_at) }}
                        </p>
                    </div>
                </a>
            </li>
        @endif

    @endforeach
@else
    <li>
        <a href="#" class=" waves-effect waves-block text-center">

            <div class="menu-info">
                <h4>@lang('notifications::notifications.no_new_notifications')</h4>
                <p>&nbsp;</p>
                <p class="text-center col-blue-grey">
                    <i class="material-icons medium-icon">notifications_none</i>
                </p>
            </div>
        </a>
    </li>
@endif