
@if(isset($btn['href']))
    {!! link_to($btn['href'],$btn['label'],$btn['attr']) !!}
@else
    {!! Form::button($btn['label'], $btn['attr']) !!}
@endif
