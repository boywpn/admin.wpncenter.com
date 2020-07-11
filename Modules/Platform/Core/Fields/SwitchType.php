<?php

namespace Modules\Platform\Core\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

/**
 * Class SwitchType
 * @package Modules\Platform\Core\Fields
 */
class SwitchType extends FormField
{
    protected $valueProperty = 'checked';

    protected function getTemplate()
    {
        return 'vendor.laravel-form-builder.switch';
    }

    /**
     * @inheritdoc
     */
    public function getDefaults()
    {
        return [
            'attr' => ['class' => null, 'id' => $this->getName()],
            'value' => 1,
            'checked' => null,
            'color' => 'switch-col-blue',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function isValidValue($value)
    {
        return $value !== null;
    }
}
