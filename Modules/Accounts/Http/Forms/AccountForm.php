<?php

namespace Modules\Accounts\Http\Forms;

use Kris\LaravelFormBuilder\Form;

use Modules\Platform\Core\Helper\FormHelper;
use Modules\Accounts\Entities\AccountType;
use Modules\Accounts\Entities\AccountIndustry;
use Modules\Accounts\Entities\AccountRating;

class AccountForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('accounts::accounts.form.name'),
        ]);

        $this->add('tax_number', 'text', [
            'label' => trans('accounts::accounts.form.tax_number'),
        ]);

        $this->add('owned_by', 'select', [
            'choices' => FormHelper::assignedToChoises(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('core::core.form.assigned_to'),
            'empty_value' => trans('core::core.empty_select'),
            'selected' => FormHelper::assignSelectedFromModel($this->model)
        ]);

        $this->add('website', 'text', [
            'label' => trans('accounts::accounts.form.website'),
        ]);

        $this->add('account_number', 'text', [
            'label' => trans('accounts::accounts.form.account_number'),
        ]);

        $this->add('annual_revenue', 'number', [
            'label' => trans('accounts::accounts.form.annual_revenue'),
        ]);

        $this->add('employees', 'number', [
            'label' => trans('accounts::accounts.form.employees'),
        ]);

        $this->add('account_type_id', 'select', [
            'choices' => AccountType::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('accounts::accounts.form.account_type_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('account_industry_id', 'select', [
            'choices' => AccountIndustry::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('accounts::accounts.form.account_industry_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('account_rating_id', 'select', [
            'choices' => AccountRating::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('accounts::accounts.form.account_rating_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('phone', 'text', [
            'label' => trans('accounts::accounts.form.phone'),
        ]);

        $this->add('email', 'text', [
            'label' => trans('accounts::accounts.form.email'),
        ]);

        $this->add('secondary_email', 'text', [
            'label' => trans('accounts::accounts.form.secondary_email'),
        ]);

        $this->add('fax', 'text', [
            'label' => trans('accounts::accounts.form.fax'),
        ]);

        $this->add('skype_id', 'text', [
            'label' => trans('accounts::accounts.form.skype_id'),
        ]);

        $this->add('street', 'text', [
            'label' => trans('accounts::accounts.form.street'),
        ]);

        $this->add('city', 'text', [
            'label' => trans('accounts::accounts.form.city'),
        ]);

        $this->add('state', 'text', [
            'label' => trans('accounts::accounts.form.state'),
        ]);

        $this->add('country', 'text', [
            'label' => trans('accounts::accounts.form.country'),
        ]);

        $this->add('zip_code', 'text', [
            'label' => trans('accounts::accounts.form.zip_code'),
        ]);

        $this->add('notes', 'textarea', [
            'label' => trans('accounts::accounts.form.notes'),
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('core::core.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
