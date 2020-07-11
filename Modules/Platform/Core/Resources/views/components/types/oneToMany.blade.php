@if(count( $entity->{$options['relation']}) > 0 )
    @foreach($entity->$fieldName as $item)
        {{ $item->{$options['column']} }}@if(!$loop->last),@endif
    @endforeach

@endif

