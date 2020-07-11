<?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
    <div <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>
<?php endif; ?>

      <div class="switch">
      <label>

          <?php if ($showLabel && $options['label'] !== false && $options['label_show']): ?>

          <?= $options['label'];?>

<?php endif; ?>

<?php if ($showField): ?>
    <?= Form::hidden($name,0) ?>
    <?= Form::checkbox($name, $options['value'], $options['checked'], $options['attr']) ?>

    <?php include(base_path().'/resources/views/vendor/laravel-form-builder/help_block.php') ?>

<?php endif; ?>

     <?php include(base_path().'/resources/views/vendor/laravel-form-builder/errors.php') ?>
        <span class="lever <?php echo $options['color']?>"></span>
      </label>
      </div>

<?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
    </div>
    <?php endif; ?>
<?php endif; ?>
