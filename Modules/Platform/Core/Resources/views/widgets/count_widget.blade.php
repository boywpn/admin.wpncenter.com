<div class="{{ $config['coll_class'] }}">
    @if($config['href'] != '')
        <a class="pointer" href="{{ $config['href'] }}">
    @endif
    <div class="{{($config['href'] != '') ? 'pointer' : '' }} info-box-2 {{ $config['bg_color'] }} hover-expand-effect">
        <div class="icon">
            @if(!strpos($config['icon'], 'fa-'))
                <i class=" material-icons {{ $config['icon_color'] }}">{{ $config['icon'] }}</i>
            @else
                <i class="{{ $config['icon'] }} {{ $config['icon_color'] }}"></i>
            @endif
        </div>
        <div class="content">
            <div class="text">{{ $config['title'] }}</div>
            <div class="number count-to" data-from="0" data-to="{{ $config['counter'] }}" data-speed="2000" data-fresh-interval="20">{{ $config['counter'] }}</div>
        </div>

    </div>
    @if($config['href'] != '')
        </a>
    @endif

</div>