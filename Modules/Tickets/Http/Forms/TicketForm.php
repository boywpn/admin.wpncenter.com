<?php

namespace Modules\Tickets\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Accounts\Entities\Account;
use Modules\Contacts\Entities\Contact;
use Modules\Platform\Core\Helper\FormHelper;
use Modules\Tickets\Entities\TicketCategory;
use Modules\Tickets\Entities\TicketPriority;
use Modules\Tickets\Entities\TicketSeverity;
use Modules\Tickets\Entities\TicketStatus;

class TicketForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('tickets::tickets.form.name'),
        ]);


        $this->add('due_date', 'dateType', [
            'label' => trans('tickets::tickets.form.due_date'),
        ]);


        $this->add('owned_by', 'select', [
            'choices' => FormHelper::assignedToChoises(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('core::core.form.assigned_to'),
            'empty_value' => trans('core::core.empty_select'),
            'selected' => FormHelper::assignSelectedFromModel($this->model)
        ]);

        $this->add('parent_id', 'manyToOne', [
            'search_route' => route('tickets.tickets.index', ['mode'=>'modal']),
            'relation' => 'parent',
            'relation_field' => 'name',
            'model' => $this->model,
            'modal_title' => 'tickets::tickets.choose',
            'attr' => ['class' => 'form-control manyToOne'],
            'label' => trans('tickets::tickets.form.parent_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('ticket_priority_id', 'select', [
            'choices' => TicketPriority::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('tickets::tickets.form.ticket_priority_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);


        $this->add('ticket_status_id', 'select', [
            'choices' => TicketStatus::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('tickets::tickets.form.ticket_status_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);


        $this->add('ticket_severity_id', 'select', [
            'choices' => TicketSeverity::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('tickets::tickets.form.ticket_severity_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);


        $this->add('ticket_category_id', 'select', [
            'choices' => TicketCategory::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('tickets::tickets.form.ticket_category_id'),
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


        $this->add('description', 'textarea', [
            'label' => trans('tickets::tickets.form.description'),
        ]);


        $this->add('resolution', 'textarea', [
            'label' => trans('tickets::tickets.form.resolution'),
        ]);


        $this->add('notes', 'textarea', [
            'label' => trans('tickets::tickets.form.notes'),
        ]);


        $this->add('submit', 'submit', [
            'label' => trans('core::core.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
