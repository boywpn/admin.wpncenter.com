<?php

namespace Modules\ContactEmails\Http\Forms;


use Kris\LaravelFormBuilder\Form;
use Modules\Contacts\Entities\Contact;

class ContactEmailForm extends Form
{
    public function buildForm()
    {

        $this->add('email', 'text', [
            'label' => trans('contactemails::contactemails.form.email'),
        ]);

        $this->add('is_default', 'checkbox', [
            'label' => trans('contactemails::contactemails.form.is_default'),
        ]);


        $this->add('is_active', 'checkbox', [
            'label' => trans('contactemails::contactemails.form.is_active'),
        ]);


        $this->add('is_marketing', 'checkbox', [
            'label' => trans('contactemails::contactemails.form.is_marketing'),
        ]);

        $this->add('contact_id', 'manyToOne', [
            'search_route' => route('contacts.contacts.index', ['mode'=>'modal']),
            'hidden' => true,
            'label_show' => false,
            'wrapper' => false,
            'relation' => 'contact',
            'relation_field' => 'full_name',
            'model' => $this->model,
            'attr' => ['class' => 'form-control manyToOne'],
            'label' => trans('core::core.form.contact_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);


        $this->add('notes', 'textarea', [
            'label' => trans('contactemails::contactemails.form.notes'),
        ]);


        $this->add('submit', 'submit', [
            'label' => trans('core::core.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);

    }

}
