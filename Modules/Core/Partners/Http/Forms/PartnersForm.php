<?php

namespace Modules\Core\Partners\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Core\Owners\Entities\Owners;

class PartnersForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('core/partners::partners.form.name'),
        ]);

        $this->add('code', 'text', [
            'label' => trans('core/partners::partners.form.code'),
        ]);

        $this->add('website', 'text', [
            'label' => trans('core/partners::partners.form.website'),
        ]);

        $this->add('phone', 'text', [
            'label' => trans('core/partners::partners.form.phone'),
        ]);

        $this->add('note', 'textarea', [
            'label' => trans('core/partners::partners.form.note'),
        ]);

        $this->add('is_active', 'checkbox', [
            'label' => trans('core/partners::partners.form.is_active'),
        ]);

        $this->add('api_active', 'checkbox', [
            'label' => trans('core/partners::partners.form.api_active'),
        ]);

        $this->add('api_show_report', 'checkbox', [
            'label' => trans('core/partners::partners.form.api_show_report'),
        ]);

        $this->add('owner_id', 'select', [
            'choices' => Owners::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('core/partners::partners.form.owner_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('core/partners::partners.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
