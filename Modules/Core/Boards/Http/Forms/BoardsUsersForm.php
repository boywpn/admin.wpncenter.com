<?php

namespace Modules\Core\Boards\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Core\Boards\Entities\Boards;

/**
 * Class PaymentForm
 * @package Modules\Payments\Http\Forms
 */
class BoardsUsersForm extends Form
{
    public function buildForm()
    {
        $this->add('username', 'text', [
            'label' => trans('core/boards::users.form.username'),
        ]);
        $this->add('code', 'text', [
            'label' => trans('core/boards::users.form.code'),
        ]);
        $this->add('password', 'password', [
            'label' => trans('core/boards::users.form.password'),
        ]);

        $this->add('is_active', 'checkbox', [
            'label' => trans('core/boards::users.form.is_active'),
            'default_value' => 1
        ]);

        $this->add('board_id', 'select', [
            'choices' => Boards::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('core/boards::users.form.board_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('core::core.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
