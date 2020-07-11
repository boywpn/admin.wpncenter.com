<?php

namespace Modules\Member\Members\Http\Forms;

use App\Models\Banks;
use Kris\LaravelFormBuilder\Form;
use Modules\Core\Agents\Entities\Agents;
use Modules\Member\Members\Entities\Members;
use Modules\Member\Members\Entities\MembersBanks;
use Modules\Member\Members\Entities\MembersStatus;
use Modules\Platform\Core\Helper\FormHelper;

/**
 * Class PaymentForm
 * @package Modules\Payments\Http\Forms
 */
class MembersBanksForm extends Form
{
    public function buildForm()
    {
        $this->add('bank_account', 'text', [
            'label' => trans('member/members::banks.form.bank_account'),
        ]);
        $this->add('bank_number', 'text', [
            'label' => trans('member/members::banks.form.bank_number'),
        ]);
        $this->add('notes', 'textarea', [
            'label' => trans('member/members::banks.form.notes'),
        ]);
        
        $this->add('is_main', 'checkbox', [
            'label' => trans('member/members::banks.form.is_main'),
            'default_value' => 0
        ]);
        $this->add('is_active', 'checkbox', [
            'label' => trans('member/members::banks.form.is_active'),
            'default_value' => 1
        ]);

        $this->add('member_id', 'select', [
            'choices' => Members::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('member/members::banks.form.member_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('bank_id', 'select', [
            'choices' => Banks::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('member/members::banks.form.bank_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('core::core.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);

        $method = FormHelper::getActionMethod();
        if($method == 'create'){
            foreach (MembersBanks::FORM_REMOVE_CREATE as $remove) {
                $this->remove($remove);
            }
        }elseif($method == 'edit'){
            foreach (MembersBanks::FORM_REMOVE_EDIT as $remove) {
                $this->remove($remove);
            }
        }
    }
}
