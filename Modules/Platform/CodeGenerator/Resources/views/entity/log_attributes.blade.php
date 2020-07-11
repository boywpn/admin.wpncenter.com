    protected static $logAttributes = [
        @foreach($entity['properties'] as $section => $field)
                @foreach($field as $key => $prop)
                        @if($prop['type'] == 'manyToOne')
                                '{{$prop['relation']}}.{{$prop['display_column']}}',
                        @elseif($prop['type'] == 'ownedBy')
                                'ownedBy.name',
                        @else
                                '{{$key}}',
                        @endif
                @endforeach
        @endforeach
    ];