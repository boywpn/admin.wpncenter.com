<div class="{{ $config['coll_class'] }}">
    <div class="info-box-3 {{ $config['bg_color'] }} hover-expand-effect">
        <div class="icon">
            <i class="material-icons">{{ $config['icon'] }}</i>
        </div>
        <div class="content">
            <div class="text">{{ $config['title'] }}</div>
            <div class="number count-to" data-from="0" data-to="{{ $config['counter'] }}" data-speed="2000" data-fresh-interval="20">{{ $config['counter'] }}</div>
        </div>
    </div>
</div>