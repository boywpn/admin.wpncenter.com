<?php

namespace Modules\Documents\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Documents\Entities\DocumentCategory;
use Modules\Documents\Entities\DocumentStatus;
use Modules\Documents\Entities\DocumentType;
use Modules\Platform\Core\Helper\FormHelper;

/**
 * Class DocumentForm
 * @package Modules\Documents\Http\Forms
 */
class DocumentForm extends Form
{
    public function buildForm()
    {
        $this->add('title', 'text', [
            'label' => trans('documents::documents.form.title'),
        ]);


        $this->add('notes', 'textarea', [
            'label' => trans('documents::documents.form.notes'),
        ]);

        $this->add('document_type_id', 'select', [
            'choices' => DocumentType::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('documents::documents.form.document_type_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('document_status_id', 'select', [
            'choices' => DocumentStatus::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('documents::documents.form.document_status_id'),
            'empty_value' => trans('core::core.empty_select'),
        ]);

        $this->add('document_category_id', 'select', [
            'choices' => DocumentCategory::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('documents::documents.form.document_category_id'),
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
