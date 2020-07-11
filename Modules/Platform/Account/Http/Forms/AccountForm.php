<?php

namespace Modules\Platform\Account\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Platform\Core\Helper\FileHelper;
use Modules\Platform\Core\Helper\ThemeHelper;
use Modules\Platform\Core\Helper\UserHelper;
use Modules\Platform\Settings\Entities\Language;
use Modules\Platform\User\Entities\DateFormat;
use Modules\Platform\User\Entities\TimeFormat;
use Modules\Platform\User\Entities\TimeZone;
use Spatie\Permission\Models\Role;

/**
 * Class AccountForm
 * @package Modules\Platform\Account\Http\Forms
 */
class AccountForm extends Form
{
    public function buildForm()
    {
        $this->add('first_name', 'text', [
            'label' => trans('account::account.form.first_name'),
        ]);
        $this->add('last_name', 'text', [
            'label' => trans('account::account.form.last_name'),
        ]);
        $this->add('title', 'text', [
            'label' => trans('account::account.form.title'),
        ]);
        $this->add('department', 'text', [
            'label' => trans('account::account.form.department'),
        ]);
        $this->add('office_phone', 'text', [
            'label' => trans('account::account.form.office_phone'),
        ]);
        $this->add('mobile_phone', 'text', [
            'label' => trans('account::account.form.mobile_phone'),
        ]);
        $this->add('home_phone', 'text', [
            'label' => trans('account::account.form.home_phone'),
        ]);
        $this->add('signature', 'text', [
            'label' => trans('account::account.form.signature'),
        ]);
        $this->add('fax', 'text', [
            'label' => trans('account::account.form.fax'),
        ]);
        $this->add('secondary_email', 'text', [
            'label' => trans('account::account.form.secondary_email'),
        ]);
        $this->add('theme', 'select', [
            'choices' => ThemeHelper::SUPPORTED_THEMES,
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('account::account.form.theme'),
            'empty_value' => trans('core::core.empty_select')
        ]);
        $this->add('address_country', 'text', [
            'label' => trans('account::account.form.address_country'),
        ]);
        $this->add('address_state', 'text', [
            'label' => trans('account::account.form.address_state'),
        ]);
        $this->add('address_city', 'text', [
            'label' => trans('account::account.form.address_city'),
        ]);
        $this->add('address_postal_code', 'text', [
            'label' => trans('account::account.form.address_postal_code'),
        ]);
        $this->add('address_street', 'text', [
            'label' => trans('account::account.form.address_street'),
        ]);
        $this->add('time_format_id', 'select', [
            'choices' => TimeFormat::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('account::account.form.time_format_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);
        $this->add('date_format_id', 'select', [
            'choices' => DateFormat::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('account::account.form.date_format_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);
        $this->add('time_zone', 'select', [
            'choices' => TimeZone::all()->pluck('name', 'php_timezone')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('account::account.form.time_zone'),
            'empty_value' => trans('core::core.empty_select')
        ]);
        $this->add('language_id', 'select', [
            'choices' => Language::all()->pluck('name', 'id')->toArray(),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('account::account.form.language_id'),
            'empty_value' => trans('core::core.empty_select')
        ]);
        $this->add('profile_pic_conf', 'select', [
            'choices' => [
                UserHelper::PROFILE_PICTURE_INITIALS => trans('account::account.initials'),
                UserHelper::PROFILE_PICTURE_GRAVATAR => trans('account::account.gravatar'),
                UserHelper::PROFILE_PICTURE_OWN => trans('account::account.image'),
            ],
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('account::account.form.profile_pic_conf'),

        ]);

        $this->add('profile_picture', 'file', [
            'label_show'=>false,
            'attr'=> ['id'=>'profile_picture'],
            'label' => trans('account::account.form.profile_picture'),
        ]);

        $this->add('submit', 'submit', [
            'label' => trans('account::account.form.save'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
