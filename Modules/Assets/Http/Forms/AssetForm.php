<?php

namespace Modules\Assets\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Accounts\Entities\Account;
use Modules\Assets\Entities\AssetCategory;
use Modules\Assets\Entities\AssetManufacturer;
use Modules\Assets\Entities\AssetStatus;
use Modules\Contacts\Entities\Contact;
use Modules\Platform\Core\Helper\FormHelper;

class AssetForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('assets::assets.form.name'),
        ]);


        $this->add('model_no', 'text', [
            'label' => trans('assets::assets.form.model_no'),
        ]);


        $this->add('tag_number', 'text', [
            'label' => trans('assets::assets.form.tag_number'),
        ]);


        $this->add('order_number', 'text', [
            'label' => trans('assets::assets.form.order_number'),
        ]);


        $this->add('purchase_date', 'dateType', [
            'label' => trans('assets::assets.form.purchase_date'),
        ]);


        $this->add('purchase_cost', 'number', [
            'label' => trans('assets::assets.form.purchase_cost'),
        ]);


        $this->add('owned_by', 'select', [
            'choices' => FormHelper::assignedToChoises(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('core::core.form.assigned_to'),
            'empty_value' => trans('core::core.empty_select'),
            'selected' => FormHelper::assignSelectedFromModel($this->model)
        ]);


        $this->add('asset_status_id', 'select', [
            'choices' => AssetStatus::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('assets::assets.form.asset_status_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);


        $this->add('asset_category_id', 'select', [
            'choices' => AssetCategory::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('assets::assets.form.asset_category_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);


        $this->add('asset_manufacturer_id', 'select', [
            'choices' => AssetManufacturer::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('assets::assets.form.asset_manufacturer_id'),
            'empty_value' => trans('core::core.empty_select')
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


        $this->add('notes', 'textarea', [
            'label' => trans('assets::assets.form.notes'),
        ]);


        $this->add('submit', 'submit', [
            'label' => trans('core::core.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
