<a href="{{ $url }}" title="{{ $name }}" class="{{ isset($cssClass) ? $cssClass : '' }}">
    @if(isset($icon))
    <i class="material-icons">{{ $icon  }}</i>
        <span>{{ $name  }}</span>
    @else
        {{ $name  }}
    @endif

</a>