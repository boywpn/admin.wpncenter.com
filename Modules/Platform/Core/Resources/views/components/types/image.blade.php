@if(!empty($entity->$fieldName))

    <img src="{{ asset($entity->$fieldName) }}" class="image_path_size" />

@endif