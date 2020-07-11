<?php

namespace Modules\Products\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Platform\Core\Helper\FormHelper;

class PriceListForm extends Form
{
    public function buildForm()
    {

        $this->add('price', 'number', [
            'label' => trans('products::price_list.form.price'),
        ]);

        $this->add('name', 'text', [
            'label' => trans('products::price_list.form.name'),
        ]);

        $this->add('product_id', 'manyToOne', [
            //TODO Fix this
            'search_route' => route('products.price_list.index', ['mode' => 'modal']),
            'hidden' => true,
            'label_show' => false,
            'wrapper' => false,
            'relation' => 'contact',
            'relation_field' => 'full_name',
            'model' => $this->model,
            'attr' => ['class' => 'form-control manyToOne'],
            'label' => trans('products::price_list.form.product_id'),
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
