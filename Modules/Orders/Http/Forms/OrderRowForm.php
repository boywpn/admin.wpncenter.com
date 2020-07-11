<?php

namespace Modules\Orders\Http\Forms;

use Kris\LaravelFormBuilder\Form;

/**
 * Class OrderRowForm
 * @package Modules\Orders\Http\Forms
 */
class OrderRowForm extends Form
{
    public function buildForm()
    {
        $this->add('id', 'hidden', [
            'label' => trans('orders::orders.form.row.product'),
            'wrapper' => ['class' => ''],
            'label_show' => false,
            'attr' => ['class' => 'form-control row_id']
        ]);
        $this->add('product_name', 'text', [
            'label' => trans('orders::orders.form.row.product'),
            'wrapper' => ['class' => 'form-group row-element'],
            'label_show' => false,
            'attr' => ['class' => 'form-control row_product_name']
        ]);

        $this->add('price', 'number', [
            'label' => trans('orders::orders.form.row.unit_cost'),
            'wrapper' => ['class' => 'form-group row-element'],
            'label_show' => false,
            'attr' => ['class' => 'form-control row_price']
        ]);

        $this->add('quantity', 'number', [
            'label' => trans('orders::orders.form.row.quantity'),
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
