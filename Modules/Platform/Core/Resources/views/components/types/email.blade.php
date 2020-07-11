@if($entity->$fieldName != '')
<a href="mailto:{{ $entity->$fieldName }}">{{ $entity->$fieldName }}</a>
@endif