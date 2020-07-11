<?php if ($showLabel && $showField): ?>
<?php if ($options['wrapper'] !== false): ?>
<div class="input-group form-group form-float manyToOne">
    <?php endif; ?>
    <?php endif; ?>

    <?php

    ?>

    <div class="form-line">

        <?php if ($showLabel && $options['label'] !== false && $options['label_show']): ?>
    <?= Form::customLabel($name, $options['label'], $options['label_attr']) ?>
<?php endif; ?>

        <?php if ($showField): ?>
        <?= Form::hidden($name, $options['value'], ['class' => 'true_value'])?>

        @if(!isset($options['hidden']))
        <?= Form::input('text', $name . '_display', $options['display_value'], ['id' => $name . '_display', 'class' => 'form-control manyToOne display_value', 'disabled' => 'disabled']) ?>

        <div class="input-group-addon">
            <i data-related-field="{{$name}}" class="material-icons clear-relation-button">clear</i>
            <i data-related-field="{{$name}}" data-route="{{$options['search_route']}}"
               data-modal-title="{{trans($options['modal_title'])}}" data-display="{{$options['relation_field']}}"
               class="material-icons search-relation-button">search</i>
        </div>
        @endif

        <?php include(base_path() . '/resources/views/vendor/laravel-form-builder/help_block.php') ?>
        <?php endif; ?>

        <?php include(base_path() . '/resources/views/vendor/laravel-form-builder/errors.php') ?>
    </div>

    <?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
</div>

<?php endif; ?>
<?php endif; ?>
