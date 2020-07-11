<?php

namespace Modules\Core\Games\Http\Forms;

use Kris\LaravelFormBuilder\Form;

class GamesForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('core/games::games.form.name'),
        ]);

        $this->add('code', 'text', [
            'label' => trans('core/games::games.form.code'),
        ]);

        $this->add('taking', 'text', [
            'label' => trans('core/games::games.form.taking'),
        ]);

        $this->add('maintenance_notes', 'text', [
            'label' => trans('core/games::games.form.maintenance_notes'),
        ]);

        $this->add('member_url', 'textarea', [
            'label' => trans('core/games::games.form.member_url'),
        ]);

        $this->add('is_active', 'checkbox', [
            'label' => trans('core/games::games.form.is_active'),
            'default_value' => 1
        ]);

        $this->add('is_commission', 'checkbox', [
            'label' => trans('core/games::games.form.is_commission'),
            'default_value' => 1
        ]);

        $this->add('is_maintenance', 'checkbox', [
            'label' => trans('core/games::games.form.is_maintenance'),
            'default_value' => 0
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('core/games::games.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
