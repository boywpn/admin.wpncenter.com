<?php

namespace Modules\Core\Promotions\Http\Forms;

use Kris\LaravelFormBuilder\Form;
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
class PromotionsForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('core/promotions::promotions.form.name'),
        ]);
        $this->add('title', 'text', [
            'label' => trans('core/promotions::promotions.form.title'),
        ]);
        $this->add('description', 'textarea', [
            'label' => trans('core/promotions::promotions.form.description'),
        ]);
        $this->add('percent', 'text', [
            'label' => trans('core/promotions::promotions.form.percent'),
        ]);
        $this->add('amount', 'text', [
            'label' => trans('core/promotions::promotions.form.amount'),
        ]);
        $this->add('min_deposit', 'text', [
            'label' => trans('core/promotions::promotions.form.min_deposit'),
        ]);
        $this->add('max_deposit', 'text', [
            'label' => trans('core/promotions::promotions.form.max_deposit'),
        ]);
        $this->add('max_value', 'text', [
            'label' => trans('core/promotions::promotions.form.max_value'),
        ]);

        $this->add('notes', 'textarea', [
            'label' => trans('core/promotions::promotions.form.notes'),
        ]);

        $this->add('is_front', 'checkbox', [
            'label' => trans('core/promotions::promotions.form.is_front'),
            'default_value' => 1
        ]);
        $this->add('is_active', 'checkbox', [
            'label' => trans('core/promotions::promotions.form.is_active'),
            'default_value' => 1
        ]);
        $this->add('have_ref', 'checkbox', [
            'label' => trans('core/promotions::promotions.form.have_ref'),
            'default_value' => 0
        ]);

        $this->add('expired_at', 'dateType', [
            'label' => trans('core/promotions::promotions.form.expired_at'),
        ]);

        $this->add('partner_id', 'select', [
            'choices' => Partners::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('core/promotions::promotions.form.partner_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('core::core.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
