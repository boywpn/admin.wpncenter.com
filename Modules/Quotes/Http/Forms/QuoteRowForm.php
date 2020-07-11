<?php

namespace Modules\Quotes\Http\Forms;

use Kris\LaravelFormBuilder\Form;

/**
 * Class QuoteRowForm
 * @package Modules\Quotes\Http\Forms
 */
class QuoteRowForm extends Form
{
    public function buildForm()
    {
        $this->add('id', 'hidden', [
            'label' => trans('quotes::quotes.form.row.product'),
            'wrapper' => ['class' => ''],
            'label_show' => false,
            'attr' => ['class' => 'form-control row_id']
        ]);

        $this->add('product_id', 'hidden', [
            'label' => trans('quotes::quotes.form.row.product'),
            'wrapper' => ['class' => ''],
            'label_show' => false,
            'attr' => ['class' => 'form-control row_product_id']
        ]);

        $this->add('product_name', 'text', [
            'label' => trans('quotes::quotes.form.row.product'),
            'wrapper' => ['class' => 'form-group row-element'],
            'label_show' => false,
            'attr' => ['class' => 'form-control row_product_name']
        ]);

        $this->add('price', 'number', [
            'label' => trans('quotes::quotes.form.row.unit_cost'),
            'wrapper' => ['class' => 'form-group row-element'],
            'label_show' => false,
            'attr' => ['class' => 'form-control row_price']
        ]);

        $this->add('quantity', 'number', [
            'label' => trans('quotes::quotes.form.row.quantity'),
            'wrapper' => ['class' => 'form-group row-element'],
            'label_show' => false,
            'attr' => ['class' => 'form-control row_quantity']
        ]);

        $this->add('lineTotal', 'static', [
            'wrapper' => ['class' => 'form-group row-element line-total-element'],
            'label_show' => false,
            'attr' => ['class' => 'form-control row_line_total']
        ]);
    }
}
