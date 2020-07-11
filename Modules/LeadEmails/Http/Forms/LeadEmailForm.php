<?php

namespace Modules\LeadEmails\Http\Forms;


use Kris\LaravelFormBuilder\Form;

class LeadEmailForm extends Form
{
    public function buildForm()
    {

        $this->add('email', 'text', [
            'label' => trans('leademails::leademails.form.email'),
        ]);

        $this->add('is_default', 'checkbox', [
            'label' => trans('leademails::leademails.form.is_default'),
        ]);


        $this->add('is_active', 'checkbox', [
            'label' => trans('leademails::leademails.form.is_active'),
        ]);


        $this->add('is_marketing', 'checkbox', [
            'label' => trans('leademails::leademails.form.is_marketing'),
        ]);

        $this->add('lead_id', 'manyToOne', [
            'search_route' => route('leads.leads.index', ['mode' => 'modal']),
            'hidden' => true,
            'label_show' => false,
            'wrapper' => false,
            'relation' => 'lead',
            'relation_field' => 'full_name',
            'model' => $this->model,
            'attr' => ['class' => 'form-control manyToOne'],
            'label' => trans('core::core.form.lead_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);


        $this->add('notes', 'textarea', [
            'label' => trans('leademails::leademails.form.notes'),
        ]);


        $this->add('submit', 'submit', [
            'label' => trans('core::core.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);

    }

}
