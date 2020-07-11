@if($entity->$fieldName != null  )
    @if(isset($options['dont_translate']))
        {{
            $entity->{$options['relation']}->{$options['column']}
        }}
    @else
        {{
            $entity->{$options['relation']}->{$options['column']}
        }}
    @endif
@endif
