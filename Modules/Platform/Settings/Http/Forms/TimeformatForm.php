<?php

namespace Modules\Platform\Settings\Http\Forms;

use Kris\LaravelFormBuilder\Form;

/**
 * Class TimeformatForm
 * @package Modules\Platform\Settings\Http\Forms
 */
class TimeformatForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('settings::timeformat.form.name'),
        ]);
        $this->add('details', 'text', [
            'label' => trans('settings::timeformat.form.details'),
        ]);
        $this->add('js_details', 'text', [
            'label' => trans('settings::timeformat.form.js_details'),
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('settings::timeformat.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
