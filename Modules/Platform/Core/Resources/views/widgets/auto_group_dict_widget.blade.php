
@foreach($records as $record)
@if($loop->iteration <= $config['max'])
<div class="{{ $config['coll_class'] }}">

    <div class="info-box-2 hover-expand-effect">
        <div class="icon">
            @if(!strpos($record['icon'], 'fa-'))
                <i class=" material-icons {{ $record['color'] }}">{{ $record['icon'] }}</i>
            @else
                <i class="{{ $record['icon'] }} {{ $record['color'] }}"></i>
            @endif
        </div>
        <div class="content">
            <div class="text">{{ $record['title'] }}</div>
            <div class="number count-to" data-from="0" data-to="{{ $record['count'] }}" data-speed="2000" data-fresh-interval="20">{{ $record['count'] }}</div>
        </div>

    </div>

</div>
@endif

@endforeach