<?php

namespace Modules\Platform\Settings\Http\Forms;

use Kris\LaravelFormBuilder\Form;

/**
 * Class TaxForm
 * @package Modules\Platform\Settings\Http\Forms
 */
class TaxForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('settings::tax.form.name'),
        ]);
        $this->add('tax_value', 'number', [
            'label' => trans('settings::tax.form.tax_value'),
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('settings::tax.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
