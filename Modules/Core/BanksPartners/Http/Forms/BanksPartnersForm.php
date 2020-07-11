<?php

namespace Modules\Core\BanksPartners\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Core\Banks\Entities\Banks;
use Modules\Core\BanksPartners\Entities\BanksPartners;
use Modules\Core\Partners\Entities\Partners;
use Modules\Member\Members\Entities\MembersStatus;
use Modules\Platform\Core\Helper\FormHelper;

/**
 * Class PaymentForm
 * @package Modules\Payments\Http\Forms
 */
class BanksPartnersForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('core/bankspartners::bankspartners.form.name'),
        ]);
        
        $this->add('notes', 'textarea', [
            'label' => trans('core/bankspartners::bankspartners.form.notes'),
        ]);
        $this->add('is_active', 'checkbox', [
            'label' => trans('core/bankspartners::bankspartners.form.is_active'),
            'default_value' => 1
        ]);

        $this->add('bank_id', 'select', [
            'choices' => Banks::getSelectOption(false),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('core/bankspartners::bankspartners.form.bank_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('partner_id', 'select', [
            'choices' => Partners::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('core/bankspartners::bankspartners.form.partner_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('member_status_id', 'select', [
            'choices' => MembersStatus::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('core/bankspartners::bankspartners.form.member_status_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('core::core.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);

        $method = FormHelper::getActionMethod();
        if($method == 'create'){
            foreach (BanksPartners::FORM_REMOVE_CREATE as $remove) {
                $this->remove($remove);
            }
        }elseif($method == 'edit'){
            foreach (BanksPartners::FORM_REMOVE_EDIT as $remove) {
                $this->remove($remove);
            }
        }
    }
}
