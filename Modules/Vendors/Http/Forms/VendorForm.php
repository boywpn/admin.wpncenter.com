<?php

namespace Modules\Vendors\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Platform\Core\Helper\FormHelper;
use Modules\Vendors\Entities\VendorCategory;

class VendorForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('vendors::vendors.form.name'),
        ]);


        $this->add('owned_by', 'select', [
            'choices' => FormHelper::assignedToChoises(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('core::core.form.assigned_to'),
            'empty_value' => trans('core::core.empty_select'),
            'selected' => FormHelper::assignSelectedFromModel($this->model)
        ]);


        $this->add('vendor_category_id', 'select', [
            'choices' => VendorCategory::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('vendors::vendors.form.vendor_category_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('phone', 'text', [
            'label' => trans('vendors::vendors.form.phone'),
        ]);


        $this->add('mobile', 'text', [
            'label' => trans('vendors::vendors.form.mobile'),
        ]);


        $this->add('email', 'text', [
            'label' => trans('vendors::vendors.form.email'),
        ]);


        $this->add('secondary_email', 'text', [
            'label' => trans('vendors::vendors.form.secondary_email'),
        ]);


        $this->add('fax', 'text', [
            'label' => trans('vendors::vendors.form.fax'),
        ]);


        $this->add('skype_id', 'text', [
            'label' => trans('vendors::vendors.form.skype_id'),
        ]);


        $this->add('street', 'text', [
            'label' => trans('vendors::vendors.form.street'),
        ]);

        $this->add('city', 'text', [
            'label' => trans('vendors::vendors.form.city'),
        ]);


        $this->add('state', 'text', [
            'label' => trans('vendors::vendors.form.state'),
        ]);


        $this->add('country', 'text', [
            'label' => trans('vendors::vendors.form.country'),
        ]);


        $this->add('zip_code', 'text', [
            'label' => trans('vendors::vendors.form.zip_code'),
        ]);


        $this->add('notes', 'textarea', [
            'label' => trans('vendors::vendors.form.notes'),
        ]);


        $this->add('submit', 'submit', [
            'label' => trans('core::core.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
