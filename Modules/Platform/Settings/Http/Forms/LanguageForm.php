<?php

namespace Modules\Platform\Settings\Http\Forms;

use Kris\LaravelFormBuilder\Form;

/**
 * Class LanguageForm
 * @package Modules\Platform\Settings\Http\Forms
 */
class LanguageForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('settings::language.form.name'),
        ]);
        $this->add('language_key', 'text', [
            'label' => trans('settings::language.form.language_key'),
        ]);
        $this->add('is_active', 'checkbox', [
            'label' => trans('settings::language.form.is_active'),
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('settings::language.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
