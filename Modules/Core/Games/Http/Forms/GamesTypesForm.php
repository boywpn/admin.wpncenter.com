<?php

namespace Modules\Core\Games\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Core\Games\Entities\Games;

class GamesTypesForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('core/games::games-types.form.name'),
        ]);

        $this->add('code', 'text', [
            'label' => trans('core/games::games-types.form.code'),
        ]);

        $this->add('start_comm', 'select', [
            'choices' => setCommissionSelect(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('core/games::games-types.form.start_comm'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('taking', 'text', [
            'label' => trans('core/games::games-types.form.taking'),
        ]);

        $this->add('is_active', 'checkbox', [
            'label' => trans('core/games::games-types.form.is_active'),
            'default_value' => 1
        ]);

        $this->add('game_id', 'select', [
            'choices' => Games::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('core/games::games-types.form.game_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('is_commission', 'checkbox', [
            'label' => trans('core/games::games-types.form.is_commission'),
            'default_value' => 1
        ]);

        $this->add('is_betlimit', 'checkbox', [
            'label' => trans('core/games::games-types.form.is_betlimit'),
            'default_value' => 0
        ]);
        $this->add('betlimit_value', 'textarea', [
            'label' => trans('core/games::games-types.form.betlimit_value'),
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('core/games::games-types.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
