<?php

namespace Modules\ServiceContracts\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Accounts\Entities\Account;
use Modules\Platform\Core\Helper\FormHelper;
use Modules\ServiceContracts\Entities\ServiceContractPriority;
use Modules\ServiceContracts\Entities\ServiceContractStatus;

class ServiceContractForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('servicecontracts::servicecontracts.form.name'),
        ]);


        $this->add('start_date', 'dateType', [
            'label' => trans('servicecontracts::servicecontracts.form.start_date'),
        ]);


        $this->add('due_date', 'dateType', [
            'label' => trans('servicecontracts::servicecontracts.form.due_date'),
        ]);


        $this->add('owned_by', 'select', [
            'choices' => FormHelper::assignedToChoises(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('core::core.form.assigned_to'),
            'empty_value' => trans('core::core.empty_select'),
            'selected' => FormHelper::assignSelectedFromModel($this->model)
        ]);


        $this->add('service_contract_priority_id', 'select', [
            'choices' => ServiceContractPriority::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('servicecontracts::servicecontracts.form.service_contract_priority_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);


        $this->add('service_contract_status_id', 'select', [
            'choices' => ServiceContractStatus::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('servicecontracts::servicecontracts.form.service_contract_status_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('account_id', 'manyToOne', [
            'search_route' => route('accounts.accounts.index', ['mode'=>'modal']),
            'relation' => 'account',
            'relation_field' => 'name',
            'model' => $this->model,
            'attr' => ['class' => 'form-control manyToOne'],
            'label' => trans('core::core.form.account_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);


        $this->add('notes', 'textarea', [
            'label' => trans('servicecontracts::servicecontracts.form.notes'),
        ]);


        $this->add('submit', 'submit', [
            'label' => trans('core::core.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
