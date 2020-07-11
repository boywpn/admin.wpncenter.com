<?php

namespace Modules\Leads\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Leads\Entities\LeadIndustry;
use Modules\Leads\Entities\LeadRating;
use Modules\Leads\Entities\LeadSource;
use Modules\Leads\Entities\LeadStatus;
use Modules\Platform\Core\Helper\FormHelper;

/**
 * Class LeadForm
 * @package Modules\Leads\Http\Forms
 */
class LeadForm extends Form
{
    public function buildForm()
    {
        $this->add('first_name', 'text', [
            'label' => trans('leads::leads.form.first_name'),
        ]);
        $this->add('last_name', 'text', [
            'label' => trans('leads::leads.form.last_name'),
        ]);

        $this->add('capture_date', 'dateTimeType', [
            'label' => trans('leads::leads.form.capture_date'),
        ]);

        $this->add('email', 'text', [
            'label' => trans('leads::leads.form.email'),
        ]);
        $this->add('secondary_email', 'text', [
            'label' => trans('leads::leads.form.secondary_email'),
        ]);

        $this->add('fax', 'text', [
            'label' => trans('leads::leads.form.fax'),
        ]);
        $this->add('annual_revenue', 'number', [
            'label' => trans('leads::leads.form.annual_revenue'),
        ]);
        $this->add('website', 'text', [
            'label' => trans('leads::leads.form.website'),
        ]);
        $this->add('no_of_employees', 'number', [
            'label' => trans('leads::leads.form.no_of_employees'),
        ]);
        $this->add('skype', 'text', [
            'label' => trans('leads::leads.form.skype'),
        ]);
        $this->add('lead_company', 'text', [
            'label' => trans('leads::leads.form.company'),
        ]);
        $this->add('job_title', 'text', [
            'label' => trans('leads::leads.form.job_title'),
        ]);
        $this->add('phone', 'text', [
            'label' => trans('leads::leads.form.phone'),
        ]);
        $this->add('mobile', 'text', [
            'label' => trans('leads::leads.form.mobile'),
        ]);
        $this->add('twitter', 'text', [
            'label' => trans('leads::leads.form.twitter'),
        ]);
        $this->add('facebook', 'text', [
            'label' => trans('leads::leads.form.facebook'),
        ]);
        $this->add('description', 'textarea', [
            'label' => trans('leads::leads.form.description'),
        ]);
        $this->add('addr_street', 'text', [
            'label' => trans('leads::leads.form.addr_street'),
        ]);
        $this->add('addr_state', 'text', [
            'label' => trans('leads::leads.form.addr_state'),
        ]);
        $this->add('addr_country', 'text', [
            'label' => trans('leads::leads.form.addr_country'),
        ]);
        $this->add('addr_city', 'text', [
            'label' => trans('leads::leads.form.addr_city'),
        ]);
        $this->add('addr_zip', 'text', [
            'label' => trans('leads::leads.form.addr_zip'),
        ]);

        $this->add('lead_status_id', 'select', [
            'choices' => LeadStatus::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('leads::leads.form.lead_status_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('lead_source_id', 'select', [
            'choices' => LeadSource::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('leads::leads.form.lead_source_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('lead_industry_id', 'select', [
            'choices' => LeadIndustry::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('leads::leads.form.lead_industry_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('lead_rating_id', 'select', [
            'choices' => LeadRating::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('leads::leads.form.lead_rating_id'),
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
