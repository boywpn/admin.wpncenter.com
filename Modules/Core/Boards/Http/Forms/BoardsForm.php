<?php

namespace Modules\Core\Boards\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Core\Agents\Entities\Agents;
use Modules\Core\Games\Entities\Games;
use Modules\Core\Partners\Entities\Partners;

/**
 * Class PaymentForm
 * @package Modules\Payments\Http\Forms
 */
class BoardsForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('core/boards::boards.form.name'),
        ]);
        $this->add('ref', 'text', [
            'label' => trans('core/boards::boards.form.ref'),
        ]);
        $this->add('user_prefix', 'text', [
            'label' => trans('core/boards::boards.form.user_prefix'),
        ]);
        $this->add('board_number', 'text', [
            'label' => trans('core/boards::boards.form.board_number'),
        ]);
        $this->add('member_prefix', 'hidden', [
            'label' => trans('core/username::username.form.member_prefix'),
        ]);
        $this->add('member_limit', 'text', [
            'label' => trans('core/boards::boards.form.member_limit'),
            'default_value' => 500
        ]);
        $this->add('notes', 'textarea', [
            'label' => trans('core/boards::boards.form.notes'),
        ]);
        $this->add('is_active', 'checkbox', [
            'label' => trans('core/boards::boards.form.is_active'),
            'default_value' => 1
        ]);
        $this->add('for_test', 'checkbox', [
            'label' => trans('core/boards::boards.form.for_test'),
            'default_value' => 0
        ]);
        $this->add('for_agent', 'checkbox', [
            'label' => trans('core/boards::boards.form.for_agent'),
            'default_value' => 0
        ]);
        $this->add('use_api', 'checkbox', [
            'label' => trans('core/boards::boards.form.use_api'),
            'default_value' => 0
        ]);
        $this->add('report_api', 'checkbox', [
            'label' => trans('core/boards::boards.form.report_api'),
            'default_value' => 0
        ]);
        $this->add('api_code', 'text', [
            'label' => trans('core/boards::boards.form.api_code'),
        ]);

        $this->add('game_id', 'select', [
            'choices' => Games::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('core/boards::boards.form.game_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('agent_id', 'select', [
            'choices' => Agents::getAgentBoardSelect(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('core/boards::boards.form.agent_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('partner_id', 'select', [
            'choices' => Partners::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('core/boards::boards.form.partner_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('core::core.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
