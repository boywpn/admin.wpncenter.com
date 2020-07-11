<?php

namespace Modules\Platform\Settings\Http\Controllers;

use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Modules\Platform\Core\Helper\EnvHelper;
use Modules\Platform\Core\Http\Controllers\AppBaseController;
use Modules\Platform\Settings\Http\Forms\OutgoingServerForm;
use Modules\Platform\Settings\Http\Forms\OutgoingServerTestMailForm;
use Modules\Platform\Settings\Http\Requests\OutgoingServerTestMailRequest;
use Modules\Platform\Settings\Http\Requests\SaveOutgoingServerRequest;

class OutgoingServerController extends AppBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    use FormBuilderTrait;

    /**
     * Show Form and load values
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $view = view('settings::outgoing_server.outgoing_server');

        $outgoingServerForm = $this->form(OutgoingServerForm::class, [
            'method' => 'POST',
            'url' => route('settings.outgoing_server'),
            'id' => 'outgoing_server_form',
            'model' => [
                'mail_driver' => config('mail.driver'),
                'mail_host' => config('mail.host'),
                'mail_port' => config('mail.port'),
                'mail_username' => config('mail.username'),
                'mail_password' => config('mail.password'),
                'mail_encryption' => config('mail.encryption')
            ]
        ]);

        $view->with('outgoing_server_form', $outgoingServerForm);

        $testEmailForm = $this->form(OutgoingServerTestMailForm::class, [
            'method' => 'POST',
            'url' => route('settings.outgoing_server_test_email'),
            'id' => 'outgoing_server_test_email_form',

        ]);


        $view->with('test_email_form', $testEmailForm);

        return $view;
    }

    public function refreshSettingsCache()
    {
        if (config('bap.demo')) {
            flash(trans('core::core.you_cant_do_that_its_demo'))->error();
            return redirect()->back();
        }

        $exitCode = \Artisan::call('config:cache');

        sleep(3);

        return redirect(route('settings.outgoing_server'));
    }

    /**
     * Update Settings
     *
     * @param SaveOutgoingServerRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(SaveOutgoingServerRequest $request)
    {
        if (config('bap.demo')) {
            flash(trans('core::core.you_cant_do_that_its_demo'))->error();
            return redirect()->back();
        }

        $form = $this->form(OutgoingServerForm::class);

        EnvHelper::updateEnv([
            'MAIL_DRIVER' => $form->getField('mail_driver')->getRawValue(),
            'MAIL_HOST' => $form->getField('mail_host')->getRawValue(),
            'MAIL_PORT' => $form->getField('mail_port')->getRawValue(),
            'MAIL_USERNAME' => $form->getField('mail_username')->getRawValue(),
            'MAIL_PASSWORD' => $form->getField('mail_password')->getRawValue(),
            'MAIL_ENCRYPTION' => ($form->getField('mail_encryption') != null) ? $form->getField('mail_encryption')->getRawValue() : null
        ]);

        flash(trans('settings::outgoing_server.settings_updated'))->success();

        return redirect(route('settings.outgoing_server'));
    }

    public function sendTestEmail(OutgoingServerTestMailRequest $request)
    {
        if (config('bap.demo')) {
            flash(trans('core::core.you_cant_do_that_its_demo'))->error();
            return redirect()->back();
        }

        $form = $this->form(OutgoingServerTestMailForm::class);

        try {
            $email = $form->getField('email')->getRawValue();
            $message = $form->getField('message')->getRawValue();
            $subject = 'TEST EMAIL';

            \Mail::send(
                'settings::outgoing_server.test_email',
                ['title' => $subject, 'content' => $message],
                function ($sendmail) use ($email, $subject) {
                    $sendmail->subject($subject);
                    $sendmail->to($email);
                }
            );

            flash(trans('settings::outgoing_server.test_email_send'))->success();

            return redirect(route('settings.outgoing_server'));
        } catch (\Exception $exception) {
            flash(
                trans('settings::outgoing_server.test_email_error') . ' ' .
                $exception->getMessage()
            )->error();


            return redirect(route('settings.outgoing_server'))->withInput();
        }
    }
}
