<?php if ($showLabel && $showField): ?>
<?php if ($options['wrapper'] !== false): ?>
<div <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>
    <?php endif; ?>

    <div class="form-line">

        <?php if ($showLabel && $options['label'] !== false && $options['label_show']): ?>
    <?= Form::customLabel($name, $options['label'], $options['label_attr']) ?>
<?php endif; ?>

        <?php if ($showField): ?>
    <?= Form::input('text', $name, $options['value'], $options['attr']) ?>

     <?php include(base_path() . '/resources/views/vendor/laravel-form-builder/help_block.php') ?>
<?php endif; ?>

        <?php include(base_path() . '/resources/views/vendor/laravel-form-builder/errors.php') ?>
    </div>
    <?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
</div>
<?php endif; ?>
<?php endif; ?>
