<?php

namespace Modules\Platform\Settings\Http\Forms;

use Kris\LaravelFormBuilder\Form;

/**
 * Class CurrencyForm
 * @package Modules\Platform\Settings\Http\Forms
 */
class CurrencyForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('settings::currency.form.name'),
        ]);
        $this->add('code', 'text', [
            'label' => trans('settings::currency.form.code'),
        ]);
        $this->add('symbol', 'text', [
            'label' => trans('settings::currency.form.symbol'),
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('settings::language.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
