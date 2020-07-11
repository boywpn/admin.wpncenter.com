<?php

namespace Modules\Calls\Http\Forms;


use Kris\LaravelFormBuilder\Form;
use Modules\Calls\Entities\DirectionType;
use Modules\Platform\Core\Helper\FormHelper;


class CallForm extends Form
{
    public function buildForm()
    {

        $this->add('subject', 'text', [
            'label' => trans('calls::calls.form.subject'),
        ]);

        $this->add('phone_number', 'text', [
            'label' => trans('calls::calls.form.phone_number'),
        ]);

        $this->add('duration', 'text', [
            'label' => trans('calls::calls.form.duration'),
        ]);


        $this->add('owned_by', 'select', [
            'choices' => FormHelper::assignedToChoises(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('core::core.form.assigned_to'),
            'empty_value' => trans('core::core.empty_select'),
            'selected' => FormHelper::assignSelectedFromModel($this->model)
        ]);


        $this->add('call_date', 'dateTimeType', [
            'label' => trans('calls::calls.form.call_date'),
        ]);


        $this->add('account_id', 'manyToOne', [
            'search_route' => route('accounts.accounts.index', ['mode'=>'modal']),
            'relation' => 'account',
            'relation_field' => 'name',
            'model' => $this->model,
            'modal_title' => 'accounts::accounts.choose',
            'attr' => ['class' => 'form-control manyToOne'],
            'label' => trans('core::core.form.account_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);


        $this->add('contact_id', 'manyToOne', [
            'search_route' => route('contacts.contacts.index', ['mode'=>'modal']),
            'relation' => 'contact',
            'relation_field' => 'full_name',
            'model' => $this->model,
            'attr' => ['class' => 'form-control manyToOne'],
            'label' => trans('core::core.form.contact_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('lead_id', 'manyToOne', [
            'search_route' => route('leads.leads.index', ['mode'=>'modal']),
            'relation' => 'lead',
            'relation_field' => 'full_name',
            'model' => $this->model,
            'attr' => ['class' => 'form-control manyToOne'],
            'label' => trans('core::core.form.lead_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('direction_id', 'select', [
            'choices' => DirectionType::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('calls::calls.form.direction_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);


        $this->add('notes', 'textarea', [
            'label' => trans('calls::calls.form.notes'),
        ]);


        $this->add('submit', 'submit', [
            'label' => trans('core::core.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);

    }

}