<?php
$tags = $entity->$fieldName;

$tagsArray = explode(',', $tags);
?>
@foreach($tagsArray as $tag)
    <span class="label label-info">{{$tag}}</span>
@endforeach
