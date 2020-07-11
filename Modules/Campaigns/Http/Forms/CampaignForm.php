<?php

namespace Modules\Campaigns\Http\Forms;

use Kris\LaravelFormBuilder\Form;

use Modules\Platform\Core\Helper\FormHelper;
use Modules\Campaigns\Entities\CampaignStatus;
use Modules\Campaigns\Entities\CampaignType;

class CampaignForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('campaigns::campaigns.form.name'),
        ]);

        $this->add('owned_by', 'select', [
            'choices' => FormHelper::assignedToChoises(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('core::core.form.assigned_to'),
            'empty_value' => trans('core::core.empty_select'),
            'selected' => FormHelper::assignSelectedFromModel($this->model)
        ]);

        $this->add('product', 'text', [
            'label' => trans('campaigns::campaigns.form.product'),
        ]);

        $this->add('target_audience', 'number', [
            'label' => trans('campaigns::campaigns.form.target_audience'),
        ]);

        $this->add('expected_close_date', 'dateType', [
            'label' => trans('campaigns::campaigns.form.expected_close_date'),
        ]);

        $this->add('sponsor', 'text', [
            'label' => trans('campaigns::campaigns.form.sponsor'),
        ]);

        $this->add('target_size', 'number', [
            'label' => trans('campaigns::campaigns.form.target_size'),
        ]);

        $this->add('campaign_status_id', 'select', [
            'choices' => CampaignStatus::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('campaigns::campaigns.form.campaign_status_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('campaign_type_id', 'select', [
            'choices' => CampaignType::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('campaigns::campaigns.form.campaign_type_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('budget_cost', 'number', [
            'label' => trans('campaigns::campaigns.form.budget_cost'),
        ]);


        $this->add('actual_budget', 'number', [
            'label' => trans('campaigns::campaigns.form.actual_budget'),
        ]);

        $this->add('expected_response', 'number', [
            'label' => trans('campaigns::campaigns.form.expected_response'),
        ]);


        $this->add('expected_revenue', 'number', [
            'label' => trans('campaigns::campaigns.form.expected_revenue'),
        ]);


        $this->add('expected_sales_count', 'number', [
            'label' => trans('campaigns::campaigns.form.expected_sales_count'),
        ]);

        $this->add('actual_sales_count', 'text', [
            'label' => trans('campaigns::campaigns.form.actual_sales_count'),
        ]);

        $this->add('expected_response_count', 'number', [
            'label' => trans('campaigns::campaigns.form.expected_response_count'),
        ]);

        $this->add('actual_response_count', 'number', [
            'label' => trans('campaigns::campaigns.form.actual_response_count'),
        ]);

        $this->add('expected_roi', 'number', [
            'label' => trans('campaigns::campaigns.form.expected_roi'),
        ]);

        $this->add('actual_roi', 'number', [
            'label' => trans('campaigns::campaigns.form.actual_roi'),
        ]);


        $this->add('notes', 'textarea', [
            'label' => trans('campaigns::campaigns.form.notes'),
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('core::core.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
