<?php


namespace Modules\Platform\Settings\Http\Forms;

use Kris\LaravelFormBuilder\Form;

/**
 * Class DateformatForm
 * @package Modules\Platform\Settings\Http\Forms
 */
class DateformatForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('settings::dateformat.form.name'),
        ]);
        $this->add('details', 'text', [
            'label' => trans('settings::dateformat.form.details'),
        ]);
        $this->add('js_details', 'text', [
            'label' => trans('settings::dateformat.form.js_details'),
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('settings::dateformat.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
