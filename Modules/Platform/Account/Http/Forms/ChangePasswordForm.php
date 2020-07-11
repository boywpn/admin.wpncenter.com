<?php

namespace Modules\Platform\Account\Http\Forms;

use Kris\LaravelFormBuilder\Form;

/**
 * Class ChangePasswordForm
 * @package Modules\Platform\Account\Http\Forms
 */
class ChangePasswordForm extends Form
{
    public function buildForm()
    {
        $this->add('password', 'password', [
            'label' => trans('account::account.form.password'),
        ]);
        $this->add('password_confirm', 'password', [
            'label' => trans('account::account.form.password_confirm'),
        ]);
        $this->add('submit', 'submit', [
            'label' => trans('account::account.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
