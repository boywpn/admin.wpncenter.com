<?php

namespace Modules\ContactRequests\Http\Forms;


use Kris\LaravelFormBuilder\Form;
use Modules\ContactRequests\Entities\ContactReason;
use Modules\ContactRequests\Entities\ContactRequestStatus;
use Modules\ContactRequests\Entities\PreferredContactMethod;
use Modules\Platform\Core\Helper\FormHelper;


class ContactRequestForm extends Form
{
    public function buildForm()
    {

        $this->add('first_name', 'text', [
            'label' => trans('contactrequests::contactrequests.form.first_name'),
        ]);


        $this->add('last_name', 'text', [
            'label' => trans('contactrequests::contactrequests.form.last_name'),
        ]);


        $this->add('organization_name', 'text', [
            'label' => trans('contactrequests::contactrequests.form.organization_name'),
        ]);


        $this->add('phone_number', 'text', [
            'label' => trans('contactrequests::contactrequests.form.phone_number'),
        ]);


        $this->add('email', 'text', [
            'label' => trans('contactrequests::contactrequests.form.email'),
        ]);


        $this->add('other_contact_method', 'text', [
            'label' => trans('contactrequests::contactrequests.form.other_contact_method'),
        ]);


        $this->add('custom_subject', 'text', [
            'label' => trans('contactrequests::contactrequests.form.custom_subject'),
        ]);


        $this->add('contact_date', 'dateTimeType', [
            'label' => trans('contactrequests::contactrequests.form.contact_date'),
        ]);


        $this->add('next_contact_date', 'dateTimeType', [
            'label' => trans('contactrequests::contactrequests.form.next_contact_date'),
        ]);


        $this->add('owned_by', 'select', [
            'choices' => FormHelper::assignedToChoises(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('core::core.form.assigned_to'),
            'empty_value' => trans('core::core.empty_select'),
            'selected' => FormHelper::assignSelectedFromModel($this->model)
        ]);


        $this->add('status_id', 'select', [
            'choices' => ContactRequestStatus::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('contactrequests::contactrequests.form.status_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);


        $this->add('preferred_id', 'select', [
            'choices' => PreferredContactMethod::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('contactrequests::contactrequests.form.preferred_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);


        $this->add('contact_reason_id', 'select', [
            'choices' => ContactReason::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('contactrequests::contactrequests.form.contact_reason_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('notes', 'textarea', [
            'label' => trans('contactrequests::contactrequests.form.notes'),
        ]);


        $this->add('submit', 'submit', [
            'label' => trans('core::core.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);

    }

}
