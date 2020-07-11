<?php

namespace Modules\Core\Owners\Http\Forms;

use Kris\LaravelFormBuilder\Form;

class OwnersForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('core/owners::owners.form.name'),
        ]);

        $this->add('code', 'text', [
            'label' => trans('core/owners::owners.form.code'),
        ]);

        $this->add('api_token', 'text', [
            'label' => trans('core/owners::owners.form.api_token'),
        ]);

        $this->add('phone', 'text', [
            'label' => trans('core/owners::owners.form.phone'),
        ]);

        $this->add('note', 'textarea', [
            'label' => trans('core/owners::owners.form.note'),
        ]);

        $this->add('is_active', 'checkbox', [
            'label' => trans('core/owners::owners.form.is_active'),
        ]);

        $this->add('is_suspend', 'checkbox', [
            'label' => trans('core/owners::owners.form.is_suspend'),
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('core/owners::owners.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
