@if($entity->$fieldName != null  )
    {{ $entity->$fieldName }}
    @if(!empty($entity->ref_id))
        <div><b>Game Username:</b> {{ $entity->ref_id }}</div>
    @endif
@endif
