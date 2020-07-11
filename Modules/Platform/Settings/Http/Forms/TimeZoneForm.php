<?php

namespace Modules\Platform\Settings\Http\Forms;

use Kris\LaravelFormBuilder\Form;

/**
 * Class TimeZoneForm
 * @package Modules\Platform\Settings\Http\Forms
 */
class TimeZoneForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('settings::timezone.form.name'),
        ]);
        $this->add('php_timezone', 'text', [
            'label' => trans('settings::timezone.form.php_timezone'),
        ]);
        $this->add('is_active', 'checkbox', [
            'label' => trans('settings::timezone.form.is_active'),
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('settings::timezone.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
