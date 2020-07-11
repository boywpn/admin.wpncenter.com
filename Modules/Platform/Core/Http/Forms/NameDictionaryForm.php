<?php

namespace Modules\Platform\Core\Http\Forms;

use Kris\LaravelFormBuilder\Form;

/**
 * Class NameDictionaryForm
 * @package Modules\Platform\Core\Http\Forms
 */
class NameDictionaryForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('core::core.form.name'),
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('core::core.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
