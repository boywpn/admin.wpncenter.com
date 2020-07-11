<?php

namespace Modules\Invoices\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Invoices\Entities\InvoiceStatus;
use Modules\Platform\Core\Helper\FormHelper;
use Modules\Platform\Settings\Entities\Currency;
use Modules\Platform\Settings\Entities\Tax;

class InvoiceForm extends Form
{
    public function buildForm()
    {
        $this->add('invoice_number', 'text', [
            'label' => trans('invoices::invoices.form.invoice_number'),
        ]);

        $this->add('order_id', 'manyToOne', [
            'search_route' => route('orders.orders.index', ['mode' => 'modal']),
            'relation' => 'order',
            'relation_field' => 'order_number',
            'model' => $this->model,
            'attr' => ['class' => 'form-control manyToOne'],
            'label' => trans('core::core.form.order_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);


        $this->add('customer_no', 'text', [
            'label' => trans('invoices::invoices.form.customer_no'),
        ]);


        $this->add('account_number', 'text', [
            'label' => trans('invoices::invoices.form.account_number'),
        ]);




        $this->add('contact_id', 'manyToOne', [
            'search_route' => route('contacts.contacts.index', ['mode' => 'modal']),
            'relation' => 'contact',
            'relation_field' => 'full_name',
            'model' => $this->model,
            'attr' => ['class' => 'form-control manyToOne'],
            'label' => trans('core::core.form.contact_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('account_id', 'manyToOne', [
            'search_route' => route('accounts.accounts.index', ['mode' => 'modal']),
            'relation' => 'account',
            'relation_field' => 'name',
            'model' => $this->model,
            'modal_title' => 'accounts::accounts.choose',
            'attr' => ['class' => 'form-control manyToOne'],
            'label' => trans('core::core.form.account_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('invoice_date', 'dateType', [
            'label' => trans('invoices::invoices.form.invoice_date'),
        ]);


        $this->add('due_date', 'dateType', [
            'label' => trans('invoices::invoices.form.due_date'),
        ]);


        $this->add('owned_by', 'select', [
            'choices' => FormHelper::assignedToChoises(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('core::core.form.assigned_to'),
            'empty_value' => trans('core::core.empty_select'),
            'selected' => FormHelper::assignSelectedFromModel($this->model)
        ]);


        $this->add('invoice_status_id', 'select', [
            'choices' => InvoiceStatus::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('invoices::invoices.form.invoice_status_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('tax_id', 'select', [
            'choices' => Tax::all()->pluck('name', 'id')->map(function ($item, $key) {
                return $item;
            })->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('invoices::invoices.form.tax_id'),
            'empty_value' => trans('core::core.empty_select'),
            'option_attributes' =>  collect(Tax::all())->mapWithKeys(function ($item) {
                return [$item->id => ['data-tax' =>  $item->tax_value]];
            })->toArray()
        ]);

        $this->add('currency_id', 'select', [
            'choices' => Currency::all()->pluck('code', 'id')->map(function ($item, $key) {
                return $item;
            })->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('invoices::invoices.form.currency_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);


        $this->add('from_tax_number', 'text', [
            'label' => trans('invoices::invoices.form.from_tax_number'),
        ]);
        $this->add('from_company', 'text', [
            'label' => trans('invoices::invoices.form.from_company'),
        ]);

        $this->add('from_street', 'text', [
            'label' => trans('invoices::invoices.form.from_street'),
        ]);

        $this->add('from_city', 'text', [
            'label' => trans('invoices::invoices.form.from_city'),
        ]);


        $this->add('from_state', 'text', [
            'label' => trans('invoices::invoices.form.from_state'),
        ]);


        $this->add('from_country', 'text', [
            'label' => trans('invoices::invoices.form.from_country'),
        ]);

        $this->add('from_zip_code', 'text', [
            'label' => trans('invoices::invoices.form.from_zip_code'),
        ]);

        $this->add('bill_tax_number', 'text', [
            'label' => trans('invoices::invoices.form.bill_tax_number'),
        ]);
        $this->add('bill_to', 'text', [
            'label' => trans('invoices::invoices.form.bill_to'),
        ]);
        $this->add('bill_street', 'text', [
            'label' => trans('invoices::invoices.form.bill_street'),
        ]);

        $this->add('bill_city', 'text', [
            'label' => trans('invoices::invoices.form.bill_city'),
        ]);


        $this->add('bill_state', 'text', [
            'label' => trans('invoices::invoices.form.bill_state'),
        ]);


        $this->add('bill_country', 'text', [
            'label' => trans('invoices::invoices.form.bill_country'),
        ]);


        $this->add('bill_zip_code', 'text', [
            'label' => trans('invoices::invoices.form.bill_zip_code'),
        ]);

        $this->add('ship_tax_number', 'text', [
            'label' => trans('invoices::invoices.form.ship_tax_number'),
        ]);
        $this->add('ship_to', 'text', [
            'label' => trans('invoices::invoices.form.ship_to'),
        ]);

        $this->add('ship_street', 'text', [
            'label' => trans('invoices::invoices.form.ship_street'),
        ]);

        $this->add('ship_city', 'text', [
            'label' => trans('invoices::invoices.form.ship_city'),
        ]);


        $this->add('ship_state', 'text', [
            'label' => trans('invoices::invoices.form.ship_state'),
        ]);


        $this->add('ship_country', 'text', [
            'label' => trans('invoices::invoices.form.ship_country'),
        ]);

        $this->add('ship_zip_code', 'text', [
            'label' => trans('invoices::invoices.form.ship_zip_code'),
        ]);


        $this->add('terms_and_cond', 'textarea', [
            'label' => trans('invoices::invoices.form.terms_and_cond'),
        ]);


        $this->add('notes', 'textarea', [
            'label' => trans('invoices::invoices.form.notes'),
        ]);


        $this->add('rows', 'collection', [
           'type' => 'form',
           'template' => 'invoices::partial.rows-edit',
            'data' => null,
            'options' => [
                'class' => InvoiceRowForm::class,
                'label' => false,
            ]
        ]);
        $this->add('delivery_cost', 'number', [
            'label' => trans('invoices::invoices.form.delivery_cost'),
        ]);
        $this->add('discount', 'number', [
            'label' => trans('invoices::invoices.form.discount'),
        ]);
        $this->add('paid', 'number', [
            'label' => trans('invoices::invoices.form.paid'),
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('core::core.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
