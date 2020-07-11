<?php

namespace Modules\Platform\Settings\Http\Forms;

use Kris\LaravelFormBuilder\Form;

/**
 * Class OutgoingServerForm
 * @package Modules\Platform\Settings\Http\Forms
 */
class OutgoingServerForm extends Form
{
    public function buildForm()
    {
        $this->add('mail_driver', 'text', [
            'label' => trans('settings::outgoing_server.mail_driver'),
        ]);
        $this->add('mail_host', 'text', [
            'label' => trans('settings::outgoing_server.mail_host')
        ]);
        $this->add('mail_port', 'text', [
            'label' => trans('settings::outgoing_server.mail_port')
        ]);
        $this->add('mail_username', 'text', [
            'label' => trans('settings::outgoing_server.mail_username')
        ]);
        $this->add('mail_password', 'text', [
            'label' => trans('settings::outgoing_server.mail_password')
        ]);
        $this->add('mail_encryption', 'text', [
            'label' => trans('settings::outgoing_server.mail_encription')
        ]);
        $this->add('submit', 'submit', [
            'label' => trans('settings::outgoing_server.update_settings'),
            'attr' => ['class'=>'btn btn-primary m-t-15 waves-effect']
        ]);
        $this->add('refresh_cache', 'button', [
            'label' => trans('settings::outgoing_server.refresh_config_cache'),
            'attr' => [
                'class'=>'btn btn-primary m-t-15 waves-effect',
                'id'=>'refresh_settings_cache',
                'btn-click'=>'1',
                'data-url'=>route('settings.outgoing_server_refresh_cache')
            ]
        ]);
    }
}
