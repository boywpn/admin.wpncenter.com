<?php

namespace Modules\Platform\Core\Http\Forms;

use Kris\LaravelFormBuilder\Form;

/**
 * Class NameIconDictionaryForm
 * @package Modules\Platform\Core\Http\Forms
 */
class NameIconDictionaryForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('core::core.form.name'),
        ]);

        $this->add('icon', 'text', [
            'label' => trans('core::core.form.icon'),
        ]);

        $this->add('color', 'text', [
            'label' => trans('core::core.form.color'),
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('core::core.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
