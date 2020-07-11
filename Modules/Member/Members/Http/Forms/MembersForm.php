<?php

namespace Modules\Member\Members\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Core\Agents\Entities\Agents;
use Modules\Member\Members\Entities\Members;
use Modules\Member\Members\Entities\MembersStatus;
use Modules\Platform\Core\Helper\FormHelper;

/**
 * Class PaymentForm
 * @package Modules\Payments\Http\Forms
 */
class MembersForm extends Form
{
    public function buildForm()
    {
        $this->add('username', 'text', [
            'label' => trans('member/members::members.form.username'),
        ]);
        $this->add('password', 'password', [
            'label' => trans('member/members::members.form.password'),
        ]);
        $this->add('name', 'text', [
            'label' => trans('member/members::members.form.name'),
        ]);
        $this->add('email', 'text', [
            'label' => trans('member/members::members.form.email'),
        ]);
        $this->add('phone', 'text', [
            'label' => trans('member/members::members.form.phone'),
        ]);
        $this->add('facebook', 'text', [
            'label' => trans('member/members::members.form.facebook'),
        ]);
        $this->add('lineid', 'text', [
            'label' => trans('member/members::members.form.lineid'),
        ]);
        $this->add('notes', 'textarea', [
            'label' => trans('member/members::members.form.notes'),
        ]);
        $this->add('is_active', 'checkbox', [
            'label' => trans('core/partners::partners.form.is_active'),
            'default_value' => 1
        ]);

        $this->add('agent_id', 'select', [
            'choices' => Agents::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('member/members::members.form.agent'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('status_id', 'select', [
            'choices' => MembersStatus::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('member/members::members.form.status'),
            'empty_value' => trans('core::core.empty_select'),
            'default_value' => 1
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('core::core.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);

        $method = FormHelper::getActionMethod();
        if($method == 'create'){
            foreach (Members::FORM_REMOVE_CREATE as $remove) {
                $this->remove($remove);
            }
        }elseif($method == 'edit'){
            foreach (Members::FORM_REMOVE_EDIT as $remove) {
                $this->remove($remove);
            }
        }
    }
}
