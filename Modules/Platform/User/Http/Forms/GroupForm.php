<?php

namespace Modules\Platform\User\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Platform\User\Entities\User;

/**
 * Class GroupForm
 * @package Modules\Platform\User\Http\Forms
 */
class GroupForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label' => trans('user::groups.form.name'),
        ]);

        if(\Auth::user()->hasPermissionTo('settings.access')){
            $users = User::all()->pluck('name', 'id')->toArray();
        }else{
            $users = User::all()->where('company_id','=',\Auth::user()->company_id)->pluck('name', 'id')->toArray();
        }

        $this->add('users', 'choice', [
            'choices' => $users,
            'attr' => ['class' => 'select2 pmd-select2 form-control'],

            'expanded' => false,
            'multiple' => true
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('user::groups.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
