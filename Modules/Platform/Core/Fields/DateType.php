<?php

namespace Modules\Platform\Core\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;
use Kris\LaravelFormBuilder\Form;
use Modules\Platform\Core\Helper\UserHelper;

/**
 * Class DateType
 * @package Modules\Platform\Core\Fields
 */
class DateType extends FormField
{
    protected function getTemplate()
    {
        return 'vendor.laravel-form-builder.datetype';
    }

    public function setValue($value)
    {
        $value = UserHelper::formatUserDate($value);

        return parent::setValue($value);
    }
}
