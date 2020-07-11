<?php

namespace Modules\Invoices\Http\Forms;

use Kris\LaravelFormBuilder\Form;

/**
 * Class InvoiceRowForm
 * @package Modules\Invoices\Http\Forms
 */
class InvoiceRowForm extends Form
{
    public function buildForm()
    {
        $this->add('id', 'hidden', [
            'label' => trans('invoices::invoices.form.row.product'),
            'wrapper' => ['class' => ''],
            'label_show' => false,
            'attr' => ['class' => 'form-control row_id']
        ]);

        $this->add('product_id', 'hidden', [
            'label' => trans('invoices::invoices.form.row.product'),
            'wrapper' => ['class' => ''],
            'label_show' => false,
            'attr' => ['class' => 'form-control row_product_id']
        ]);

        $this->add('price_list_id', 'hidden', [
            'label' => trans('invoices::invoices.form.row.price_list_id'),
            'wrapper' => ['class' => ''],
            'label_show' => false,
            'attr' => ['class' => 'form-control row_price_list_id']
        ]);

        $this->add('product_name', 'text', [
            'label' => trans('invoices::invoices.form.row.product'),
            'wrapper' => ['class' => 'form-group row-element'],
            'label_show' => false,
            'attr' => ['class' => 'form-control row_product_name']
        ]);

        $this->add('price', 'number', [
            'label' => trans('invoices::invoices.form.row.unit_cost'),
            'wrapper' => ['class' => 'form-group row-element'],
            'label_show' => false,
            'attr' => ['class' => 'form-control row_price']
        ]);

        $this->add('quantity', 'number', [
            'label' => trans('invoices::invoices.form.row.quantity'),
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
