<?php

namespace Modules\Quotes\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Platform\Core\Helper\FormHelper;
use Modules\Platform\Settings\Entities\Currency;
use Modules\Platform\Settings\Entities\Tax;
use Modules\Quotes\Entities\QuoteCarrier;
use Modules\Quotes\Entities\QuoteStage;

class QuoteForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('quotes::quotes.form.name'),
        ]);


        $this->add('owned_by', 'select', [
            'choices' => FormHelper::assignedToChoises(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('core::core.form.assigned_to'),
            'empty_value' => trans('core::core.empty_select'),
            'selected' => FormHelper::assignSelectedFromModel($this->model)
        ]);


        $this->add('valid_unitl', 'dateType', [
            'label' => trans('quotes::quotes.form.valid_unitl'),
        ]);

        $this->add('amount', 'number', [
            'label' => trans('quotes::quotes.form.amount'),
        ]);


        $this->add('shipping', 'text', [
            'label' => trans('quotes::quotes.form.shipping'),
        ]);


        $this->add('quote_stage_id', 'select', [
            'choices' => QuoteStage::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('quotes::quotes.form.quote_stage_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);


        $this->add('quote_carrier_id', 'select', [
            'choices' => QuoteCarrier::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('quotes::quotes.form.quote_carrier_id'),
            'empty_value' => trans('core::core.empty_select')
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
            'attr' => ['class' => 'form-control manyToOne'],
            'label' => trans('core::core.form.account_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);


        $this->add('street', 'text', [
            'label' => trans('quotes::quotes.form.street'),
        ]);

        $this->add('city', 'text', [
            'label' => trans('quotes::quotes.form.city'),
        ]);


        $this->add('state', 'text', [
            'label' => trans('quotes::quotes.form.state'),
        ]);


        $this->add('country', 'text', [
            'label' => trans('quotes::quotes.form.country'),
        ]);


        $this->add('zip_code', 'text', [
            'label' => trans('quotes::quotes.form.zip_code'),
        ]);


        $this->add('notes', 'textarea', [
            'label' => trans('quotes::quotes.form.notes'),
        ]);

        $this->add('tax_id', 'select', [
            'choices' => Tax::all()->pluck('name', 'id')->map(function ($item, $key) {
                return $item;
            })->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('quotes::quotes.form.tax_id'),
            'empty_value' => trans('core::core.empty_select'),
            'option_attributes' => collect(Tax::all())->mapWithKeys(function ($item) {
                return [$item->id => ['data-tax' => $item->tax_value]];
            })->toArray()
        ]);

        $this->add('currency_id', 'select', [
            'choices' => Currency::all()->pluck('code', 'id')->map(function ($item, $key) {
                return $item;
            })->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('quotes::quotes.form.currency_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('delivery_cost', 'number', [
            'label' => trans('quotes::quotes.form.delivery_cost'),
        ]);
        $this->add('discount', 'number', [
            'label' => trans('quotes::quotes.form.discount'),
        ]);

        $this->add('rows', 'collection', [
            'type' => 'form',
            'template' => 'quotes::partial.rows-edit',
            'data' => null,
            'options' => [
                'class' => QuoteRowForm::class,
                'label' => false,
            ]
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('core::core.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
