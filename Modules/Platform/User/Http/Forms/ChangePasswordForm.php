<?php

namespace Modules\Platform\User\Http\Forms;

use Kris\LaravelFormBuilder\Form;

/**
 * Class ChangePasswordForm
 * @package Modules\Platform\User\Http\Forms
 */
class ChangePasswordForm extends Form
{
    public function buildForm()
    {
        $this->add('password', 'password', [
            'label' => trans('user::users.form.password'),
        ]);
        $this->add('password_confirm', 'password', [
            'label' => trans('user::users.form.password_confirm'),
        ]);
        $this->add('submit', 'submit', [
            'label' => trans('user::roles.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
