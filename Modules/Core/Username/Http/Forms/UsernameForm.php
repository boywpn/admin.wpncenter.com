<?php

namespace Modules\Core\Username\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Core\Boards\Entities\Boards;
use Modules\Core\Username\Entities\Username;
use Modules\Member\Members\Entities\Members;
use Modules\Platform\Core\Helper\FormHelper;

/**
 * Class PaymentForm
 * @package Modules\Payments\Http\Forms
 */
class UsernameForm extends Form
{
    public function buildForm()
    {
        $this->add('username', 'hidden', [
            'label' => trans('core/username::username.form.username'),
//            'attr' => ['readonly'=>'readonly'],
//            'default_value' => trans('core/username::username.form.help_username')
        ]);
        $this->add('password', 'password', [
            'label' => trans('core/username::username.form.password'),
        ]);
        $this->add('code', 'text', [
            'label' => trans('core/username::username.form.code'),
        ]);
        $this->add('notes', 'textarea', [
            'label' => trans('core/username::username.form.notes'),
        ]);
        $this->add('is_active', 'checkbox', [
            'label' => trans('core/username::username.form.is_active'),
            'default_value' => 1
        ]);

//        $this->add('bet_limits', 'select', [
//            'label' => trans('core/username::username.form.bet_limits'),
//            'choices' => [
//                '0' => '5 - 3,000',
//                '1' => '20 - 5,000',
//                '2' => '100 - 10,000',
//                '3' => '500 - 50,000',
//                '4' => '1,000 - 100,000',
//                '5' => '10,000 - 200,000'
//            ],
//            'attr' => ['multiple'=>'multiple']
//        ]);

        $this->add('board_id', 'select', [
            'choices' => Boards::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('core/username::username.form.board_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('member_id', 'select', [
            'choices' => Members::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('core/username::username.form.member_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('core::core.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);

        $method = FormHelper::getActionMethod();
        if($method == 'create'){
            foreach (Username::FORM_REMOVE_CREATE as $remove) {
                $this->remove($remove);
            }
        }elseif($method == 'edit'){
            foreach (Username::FORM_REMOVE_EDIT as $remove) {
                $this->remove($remove);
            }
        }
    }
}
