<?php

namespace Modules\Platform\Core\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

/**
 * Class SimpleColorPicker
 * @package Modules\Platform\Core\Fields
 */
class SimpleColorPicker extends FormField
{
    protected function getTemplate()
    {
        return 'vendor.laravel-form-builder.simple-color-picker';
    }

    /**
     * @inheritdoc
     */
    public function getDefaults()
    {
        return [
            'attr' => ['class' => 'form-control colorpicker', 'id' => $this->getName()],

        ];
    }
}
