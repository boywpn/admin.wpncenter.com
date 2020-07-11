<?php

namespace Modules\Orders\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Accounts\Entities\Account;
use Modules\Contacts\Entities\Contact;
use Modules\Deals\Entities\Deal;
use Modules\Orders\Entities\OrderCarrier;
use Modules\Orders\Entities\OrderStatus;
use Modules\Platform\Core\Helper\FormHelper;
use Modules\Platform\Settings\Entities\Currency;
use Modules\Platform\Settings\Entities\Tax;

class OrderForm extends Form
{
    public function buildForm()
    {
        $this->add('order_number', 'text', [
            'label' => trans('orders::orders.form.order_number'),
        ]);


        $this->add('carrier_number', 'text', [
            'label' => trans('orders::orders.form.carrier_number'),
        ]);

        $this->add('deal_id', 'manyToOne', [
            'search_route' => route('deals.deals.index', ['mode'=>'modal']),
            'relation' => 'deal',
            'relation_field' => 'name',
            'model' => $this->model,
            'attr' => ['class' => 'form-control manyToOne'],
            'label' => trans('orders::orders.form.deal_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);


        $this->add('customer_no', 'text', [
            'label' => trans('orders::orders.form.customer_no'),
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

        $this->add('account_id', 'manyToOne', [
            'search_route' => route('accounts.accounts.index', ['mode'=>'modal']),
            'relation' => 'account',
            'relation_field' => 'name',
            'model' => $this->model,
            'attr' => ['class' => 'form-control manyToOne'],
            'label' => trans('core::core.form.account_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);


        $this->add('purchase_order', 'text', [
            'label' => trans('orders::orders.form.purchase_order'),
        ]);


        $this->add('due_date', 'dateType', [
            'label' => trans('orders::orders.form.due_date'),
        ]);

        $this->add('order_date', 'dateType', [
            'label' => trans('orders::orders.form.order_date'),
        ]);

        $this->add('owned_by', 'select', [
            'choices' => FormHelper::assignedToChoises(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('core::core.form.assigned_to'),
            'empty_value' => trans('core::core.empty_select'),
            'selected' => FormHelper::assignSelectedFromModel($this->model)
        ]);


        $this->add('order_status_id', 'select', [
            'choices' => OrderStatus::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('orders::orders.form.order_status_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);


        $this->add('order_carrier_id', 'select', [
            'choices' => OrderCarrier::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('orders::orders.form.order_carrier_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('bill_to', 'text', [
            'label' => trans('orders::orders.form.bill_to'),
        ]);

        $this->add('bill_tax_number', 'text', [
            'label' => trans('orders::orders.form.bill_tax_number'),
        ]);


        $this->add('bill_street', 'text', [
            'label' => trans('orders::orders.form.bill_street'),
        ]);

        $this->add('bill_city', 'text', [
            'label' => trans('orders::orders.form.bill_city'),
        ]);


        $this->add('bill_state', 'text', [
            'label' => trans('orders::orders.form.bill_state'),
        ]);


        $this->add('bill_country', 'text', [
            'label' => trans('orders::orders.form.bill_country'),
        ]);


        $this->add('bill_zip_code', 'text', [
            'label' => trans('orders::orders.form.bill_zip_code'),
        ]);

        $this->add('ship_to', 'text', [
            'label' => trans('orders::orders.form.ship_to'),
        ]);

        $this->add('ship_tax_number', 'text', [
            'label' => trans('orders::orders.form.ship_tax_number'),
        ]);

        $this->add('ship_street', 'text', [
            'label' => trans('orders::orders.form.ship_street'),
        ]);

        $this->add('ship_city', 'text', [
            'label' => trans('orders::orders.form.ship_city'),
        ]);


        $this->add('ship_state', 'text', [
            'label' => trans('orders::orders.form.ship_state'),
        ]);


        $this->add('ship_country', 'text', [
            'label' => trans('orders::orders.form.ship_country'),
        ]);


        $this->add('ship_zip_code', 'text', [
            'label' => trans('orders::orders.form.ship_zip_code'),
        ]);


        $this->add('terms_and_cond', 'textarea', [
            'label' => trans('orders::orders.form.terms_and_cond'),
        ]);


        $this->add('notes', 'textarea', [
            'label' => trans('orders::orders.form.notes'),
        ]);

        $this->add('tax_id', 'select', [
            'choices' => Tax::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('orders::orders.form.tax_id'),
            'empty_value' => trans('core::core.empty_select'),
            'option_attributes' =>  collect(Tax::all())->mapWithKeys(function ($item) {
                return [$item->id => ['data-tax' =>  $item->tax_value]];
            })->toArray()
        ]);

        $this->add('currency_id', 'select', [
            'choices' => Currency::all()->pluck('code', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('orders::orders.form.currency_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);


        $this->add('rows', 'collection', [
            'type' => 'form',
            'template' => 'orders::partial.rows-edit',
            'data' => null,
            'options' => [
                'class' => OrderRowForm::class,
                'label' => false,
            ]
        ]);
        $this->add('delivery_cost', 'number', [
            'label' => trans('orders::orders.form.delivery_cost'),
        ]);
        $this->add('discount', 'number', [
            'label' => trans('orders::orders.form.discount'),
        ]);
        $this->add('paid', 'number', [
            'label' => trans('orders::orders.form.paid'),
        ]);


        $this->add('submit', 'submit', [
            'label' => trans('core::core.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
