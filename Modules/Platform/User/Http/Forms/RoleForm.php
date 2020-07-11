<?php

namespace Modules\Platform\User\Http\Forms;

use Kris\LaravelFormBuilder\Form;

/**
 * Class RoleForm
 * @package Modules\Platform\User\Http\Forms
 */
class RoleForm extends Form
{
    public function buildForm()
    {
        $this->add('display_name', 'text', [
            'label' => trans('user::roles.form.display_name'),
        ]);
        $this->add('name', 'text', [
            'label' => trans('user::roles.form.name'),
        ]);
        $this->add('guard_name', 'text', [
            'label' => trans('user::roles.form.guard_name'),
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('user::roles.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
