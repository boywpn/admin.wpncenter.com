
<?php

$size = config('bap.gravatar_display_size');
?>
@if(!empty($entity->$fieldName))

    <?php

    $imageUrl = config('app.url') . '/' . config('contacts.profile_picture_path') . $entity->$fieldName;

    $format = '<img src="%1$s" width="%2$s" heigh="%2$s" />';

    echo sprintf($format, $imageUrl, $size);

    ?>

@else

    <?php

    $image = md5(strtolower($entity->email));

    $imageUrl = "https://www.gravatar.com/avatar/" . $image . "?s=" . $size . "&d=".config('bap.gravatar_default_preview')."&r=PG";

    $format = '<img src="%1$s" width="%2$s" heigh="%2$s" />';

    echo sprintf($format, $imageUrl, $size);

    ?>

@endif

