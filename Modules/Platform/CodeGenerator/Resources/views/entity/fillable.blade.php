public $fillable = [
@foreach($entity['properties'] as $section => $field)
    @foreach($field as $key => $prop)
        @if($prop['type'] != 'ownedBy' )
            '{{$key}}',
        @endif
    @endforeach
@endforeach
];
