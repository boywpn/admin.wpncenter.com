<?php

namespace Modules\Products\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Platform\Core\Helper\FormHelper;
use Modules\Products\Entities\ProductCategory;
use Modules\Products\Entities\ProductType;
use Modules\Vendors\Entities\Vendor;

class ProductForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('products::products.form.name'),
        ]);

        $this->add('image_path', 'file', [
            'label_show'=>false,
            'attr'=> ['id'=>'image_path'],
            'label' => trans('products::products.form.image_path'),
        ]);


        $this->add('part_number', 'text', [
            'label' => trans('products::products.form.part_number'),
        ]);


        $this->add('vendor_part_number', 'text', [
            'label' => trans('products::products.form.vendor_part_number'),
        ]);


        $this->add('product_sheet', 'text', [
            'label' => trans('products::products.form.product_sheet'),
        ]);


        $this->add('website', 'text', [
            'label' => trans('products::products.form.website'),
        ]);


        $this->add('serial_no', 'text', [
            'label' => trans('products::products.form.serial_no'),
        ]);


        if($this->model != null && !is_array($this->model) && $this->model->priceList->count() > 0 ){
            $this->add('price', 'number', [
                'attr' => ['class' => 'form-control read-only', 'readonly' => 'readonly'],
                'label' => trans('products::products.form.price'),
            ]);
        }else{
            $this->add('price', 'number', [
                'label' => trans('products::products.form.price'),
            ]);
        }



        $this->add('owned_by', 'select', [
            'choices' => FormHelper::assignedToChoises(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('core::core.form.assigned_to'),
            'empty_value' => trans('core::core.empty_select'),
            'selected' => FormHelper::assignSelectedFromModel($this->model)
        ]);

        $this->add('vendor_id', 'manyToOne', [
            'search_route' => route('vendors.vendors.index', ['mode'=>'modal']),
            'relation' => 'vendor',
            'relation_field' => 'name',
            'model' => $this->model,
            'attr' => ['class' => 'form-control manyToOne'],
            'label' => trans('core::core.form.vendor_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);


        $this->add('product_type_id', 'select', [
            'choices' => ProductType::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('products::products.form.product_type_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);


        $this->add('product_category_id', 'select', [
            'choices' => ProductCategory::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('products::products.form.product_category_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);


        $this->add('notes', 'textarea', [
            'label' => trans('products::products.form.notes'),
        ]);


        $this->add('submit', 'submit', [
            'label' => trans('core::core.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
