<?php

namespace Modules\Platform\User\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Core\Owners\Entities\Owners;
use Modules\Core\Partners\Entities\Partners;
use Modules\Platform\Companies\Entities\Company;
use Modules\Platform\Core\Helper\FileHelper;
use Modules\Platform\Core\Helper\ThemeHelper;
use Modules\Platform\Core\Helper\UserHelper;
use Modules\Platform\Settings\Entities\Language;
use Modules\Platform\User\Entities\DateFormat;
use Modules\Platform\User\Entities\TimeFormat;
use Modules\Platform\User\Entities\TimeZone;
use Spatie\Permission\Models\Role;

/**
 * Class UserForm
 * @package Modules\Platform\User\Http\Forms
 */
class UserForm extends Form
{
    public function buildForm()
    {
        $this->add('email', 'email', [
            'label' => trans('user::users.form.email'),
        ]);

        if(\Auth::user()->hasPermissionTo('settings.access')){
            $roles = Role::all()->pluck('display_name', 'name')->toArray();

            $this->add('company_id', 'select', [
                'choices' => Company::all()->pluck('name', 'id')->toArray(),
                'attr' => ['class' => 'select2 pmd-select2 form-control'],
                'label' => trans('user::users.form.company_id'),
                'empty_value' => trans('core::core.empty_select')
            ]);

            $this->add('token', 'text', [
                'label' => trans('user::users.form.token'),
            ]);

        }else{
            $roles = Role::where('id','<>',1)->pluck('display_name', 'name')->toArray();
        }



        $this->add('roles', 'choice', [
            'choices' => $roles,
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'selected' => $this->model ? $this->model->roles()->pluck('name')->toArray() : null ,
            'expanded' => false,
            'multiple' => true
        ]);

        $this->add('first_name', 'text', [
            'label' => trans('user::users.form.first_name'),
        ]);
        $this->add('last_name', 'text', [
            'label' => trans('user::users.form.last_name'),
        ]);
        $this->add('access_to_all_entity', 'switch', [
            'label' => trans('user::users.form.access_to_all_entity'),
            'color' => 'switch-col-red'
        ]);
        $this->add('is_active', 'switch', [
            'label' => trans('user::users.form.is_active'),
            'color' => 'switch-col-red'
        ]);
        $this->add('title', 'text', [
            'label' => trans('user::users.form.title'),
        ]);
        $this->add('department', 'text', [
            'label' => trans('user::users.form.department'),
        ]);
        $this->add('office_phone', 'text', [
            'label' => trans('user::users.form.office_phone'),
        ]);
        $this->add('mobile_phone', 'text', [
            'label' => trans('user::users.form.mobile_phone'),
        ]);
        $this->add('home_phone', 'text', [
            'label' => trans('user::users.form.home_phone'),
        ]);
        $this->add('signature', 'text', [
            'label' => trans('user::users.form.signature'),
        ]);
        $this->add('fax', 'text', [
            'label' => trans('user::users.form.fax'),
        ]);
        $this->add('secondary_email', 'text', [
            'label' => trans('user::users.form.secondary_email'),
        ]);
        $this->add('theme', 'select', [
            'choices' => ThemeHelper::SUPPORTED_THEMES,
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('user::users.form.theme'),
            'empty_value' => trans('core::core.empty_select')
        ]);
        $this->add('address_country', 'text', [
            'label' => trans('user::users.form.address_country'),
        ]);
        $this->add('address_state', 'text', [
            'label' => trans('user::users.form.address_state'),
        ]);
        $this->add('address_city', 'text', [
            'label' => trans('user::users.form.address_city'),
        ]);
        $this->add('address_postal_code', 'text', [
            'label' => trans('user::users.form.address_postal_code'),
        ]);
        $this->add('address_street', 'text', [
            'label' => trans('user::users.form.address_street'),
        ]);
        $this->add('time_format_id', 'select', [
            'choices' => TimeFormat::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('user::users.form.time_format_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);
        $this->add('date_format_id', 'select', [
            'choices' => DateFormat::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('user::users.form.date_format_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);
        $this->add('time_zone', 'select', [
            'choices' => TimeZone::all()->pluck('name', 'php_timezone')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('user::users.form.time_zone'),
            'empty_value' => trans('core::core.empty_select')
        ]);
        $this->add('language_id', 'select', [
            'choices' => Language::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('user::users.form.language_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);
        $this->add('profile_pic_conf', 'select', [
            'choices' => [
                UserHelper::PROFILE_PICTURE_INITIALS => trans('user::users.initials'),
                UserHelper::PROFILE_PICTURE_GRAVATAR => trans('user::users.gravatar'),
                UserHelper::PROFILE_PICTURE_OWN => trans('user::users.image'),
            ],
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('user::users.form.profile_pic_conf'),

        ]);
        $this->add('owner_id', 'select', [
            'choices' => Owners::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('user::users.form.owner_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);

        $this->add('profile_picture', 'file', [
            'label_show'=>false,
            'attr'=> ['id'=>'profile_picture'],
            'label' => trans('user::users.form.profile_picture'),
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('user::users.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
