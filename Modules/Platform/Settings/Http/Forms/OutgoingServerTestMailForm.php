<?php

namespace Modules\Platform\Settings\Http\Forms;

use Kris\LaravelFormBuilder\Form;

/**
 * Class OutgoingServerTestMailForm
 * @package Modules\Platform\Settings\Http\Forms
 */
class OutgoingServerTestMailForm extends Form
{
    public function buildForm()
    {
        $this->add('email', 'text', [
            'label' => trans('settings::outgoing_server.email'),
        ]);
        $this->add('message', 'textarea', [
            'label' => trans('settings::outgoing_server.message')
        ]);
        $this->add('submit', 'submit', [
            'label' => trans('settings::outgoing_server.send_test_email'),
            'attr' => ['class' => 'btn btn-primary m-t-15 waves-effect']
        ]);
    }
}
