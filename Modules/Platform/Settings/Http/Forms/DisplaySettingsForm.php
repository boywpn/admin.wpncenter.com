<?php

namespace Modules\Platform\Settings\Http\Forms;

use Kris\LaravelFormBuilder\Form;
use Modules\Platform\Core\Helper\FileHelper;

/**
 * Class DisplaySettingsForm
 * @package Modules\Platform\Settings\Http\Forms
 */
class DisplaySettingsForm extends Form
{
    public function buildForm()
    {
        $this->add('logo_separator', 'static', [
            'label_show' => false,
            'tag' => 'h2',
            'attr' => ['class' => 'card-inside-title'],
            'value' => trans('settings::display.logo_settings')
        ]);

        $this->add('s_display_logo_upload', 'file', [
            'label' => trans('settings::display.logo'),
        ]);

        $this->add('s_display_pdf_logo_upload', 'file', [
            'label' => trans('settings::display.pdf_logo'),
        ]);

        $this->add('s_display_show_logo_in_application', 'switch', [
            'label' => trans('settings::display.show_logo_in_application'),
            'color' => 'switch-col-red'
        ]);
        $this->add('s_display_show_logo_in_pdf', 'switch', [
            'label' => trans('settings::display.show_logo_in_pdf'),
            'color' => 'switch-col-red'
        ]);

        $this->add('separator', 'static', [
            'label_show' => false,
            'tag' => 'h2',
            'attr' => ['class' => 'card-inside-title'],
            'value' => trans('settings::display.application_display_settings')
        ]);

        $this->add('s_display_application_name', 'text', [
            'label' => trans('settings::display.application_name'),
        ]);

        $this->add('s_display_sidebar_background', 'select', [
            'choices' => FileHelper::listFiles(base_path() . '/public/bg/sidebar'),
            'attr' => ['class' => 'select2 pmd-select2 form-control'],
            'label' => trans('settings::display.sidebar_background'),
            'empty_value' => trans('core::core.empty_select')
        ]);


        $this->add('submit', 'submit', [
            'label' => trans('settings::display.update_settings'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']

        ]);
    }
}
