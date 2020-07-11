<?php

namespace Modules\Platform\MenuManager\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use Spatie\Permission\Models\Permission;

/**
 * Class MenuForm
 * @package Modules\Platform\MenuManager\Http\Forms
 */
class MenuForm extends Form
{
    public function buildForm()
    {
        $this->add('id', 'hidden');
        $this->add('label', 'text', [
            'label' => trans('menumanager::menu_manager.form.label'),
        ]);

        $this->add('visibility', 'checkbox', [
            'label' => trans('menumanager::menu_manager.form.visibility'),

        ]);

        $this->add('dont_translate', 'checkbox', [
            'label' => trans('menumanager::menu_manager.form.dont_translate'),

        ]);

        $this->add('icon', 'text', [
            'label' => trans('menumanager::menu_manager.form.icon'),
        ]);
        $this->add('url', 'text', [
            'label' => trans('menumanager::menu_manager.form.url'),
        ]);


        $this->add('permission', 'choice', [
            'choices' => Permission::all()->pluck('name', 'name')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('menumanager::menu_manager.form.permission'),
            'selected' => $this->model ? $this->model->permission : '' ,
            'empty_value' => trans('menumanager::menu_manager.empty_select')
        ]);
        $this->add('submit', 'submit', [
            'label' => trans('menumanager::menu_manager.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
