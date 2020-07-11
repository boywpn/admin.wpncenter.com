<?php

namespace Modules\Payments\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Payments\Entities\PaymentCategory;
use Modules\Payments\Entities\PaymentPaymentMethod;
use Modules\Payments\Entities\PaymentStatus;
use Modules\Platform\Core\Helper\FormHelper;
use Modules\Platform\Settings\Entities\Currency;

/**
 * Class PaymentForm
 * @package Modules\Payments\Http\Forms
 */
class PaymentForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('payments::payments.form.name'),
        ]);
        $this->add('income', 'checkbox', [
            'label' => trans('payments::payments.form.income'),
        ]);
        $this->add('payment_date', 'dateType', [
            'label' => trans('payments::payments.form.payment_date'),
        ]);
        $this->add('amount', 'number', [
            'label' => trans('payments::payments.form.amount'),
        ]);
        $this->add('notes', 'textarea', [
            'label' => trans('payments::payments.form.notes'),
        ]);

        $this->add('payment_status_id', 'select', [
            'choices' => PaymentStatus::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('payments::payments.form.payment_status_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('payment_category_id', 'select', [
            'choices' => PaymentCategory::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('payments::payments.form.payment_category_id'),
            'empty_value' => trans('core::core.empty_select'),

        ]);

        $this->add('payment_currency_id', 'select', [
            'choices' => Currency::all()->pluck('code', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('payments::payments.form.payment_currency_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('payment_payment_method_id', 'select', [
            'choices' => PaymentPaymentMethod::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('payments::payments.form.payment_payment_method_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('owned_by', 'select', [
            'choices' => FormHelper::assignedToChoises(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('core::core.form.assigned_to'),
            'empty_value' => trans('core::core.empty_select'),
            'selected' => FormHelper::assignSelectedFromModel($this->model)
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('core::core.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
