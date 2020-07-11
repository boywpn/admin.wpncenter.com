<div class="info-box">
    <div class="icon {{ $config['color'] }}">
        <i class="material-icons ">face</i>
    </div>
    <div class="content">
        <div class="text">@lang('settings::widgets.users_count.'.$config['widget_title'])</div>
        <div class="number count-to" data-from="0" data-to="{{ $userCount }}" data-speed="1000" data-fresh-interval="20">{{ $userCount }}</div>
    </div>
</div>