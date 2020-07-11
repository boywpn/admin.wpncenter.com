<?php

namespace Modules\Core\Agents\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Core\Agents\Entities\Agents;
use Modules\Core\Agents\Entities\AgentsStatus;
use Modules\Core\Partners\Entities\Partners;
use Modules\Payments\Entities\PaymentCategory;
use Modules\Payments\Entities\PaymentPaymentMethod;
use Modules\Payments\Entities\PaymentStatus;
use Modules\Platform\Core\Helper\FormHelper;
use Modules\Platform\Settings\Entities\Currency;

/**
 * Class PaymentForm
 * @package Modules\Payments\Http\Forms
 */
class AgentsForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('core/agents::agents.form.name'),
        ]);
        $this->add('ref', 'text', [
            'label' => trans('core/agents::agents.form.ref'),
        ]);
        $this->add('email', 'text', [
            'label' => trans('core/agents::agents.form.email'),
        ]);
        $this->add('phone', 'text', [
            'label' => trans('core/agents::agents.form.phone'),
        ]);
        $this->add('notes', 'textarea', [
            'label' => trans('core/agents::agents.form.notes'),
        ]);
        $this->add('is_active', 'checkbox', [
            'label' => trans('core/partners::partners.form.is_active'),
            'default_value' => 1
        ]);

        $this->add('parent_id', 'select', [
            'choices' => Agents::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('core/agents::agents.form.parent_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('partner_id', 'select', [
            'choices' => Partners::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('core/agents::agents.form.partner'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('status_id', 'select', [
            'choices' => AgentsStatus::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('core/agents::agents.form.status'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('core::core.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
