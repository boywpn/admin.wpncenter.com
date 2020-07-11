<?php

namespace Modules\Platform\Announcement\Http\Controllers;

use Kris\LaravelFormBuilder\FormBuilderTrait;
use Krucas\Settings\Facades\Settings;
use Modules\Platform\Announcement\Http\Forms\AnnouncementForm;
use Modules\Platform\Announcement\Http\Requests\SaveAnnouncementRequest;
use Modules\Platform\Core\Helper\SettingsHelper;
use Modules\Platform\Core\Http\Controllers\AppBaseController;

/**
 * Class AnnouncementController
 * @package Modules\Platform\Announcement\Http\Controllers
 */
class AnnouncementController extends AppBaseController
{

    /**
     * AnnouncementController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    use FormBuilderTrait;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $view = view('announcement::index');

        $announcementForm = $this->form(AnnouncementForm::class, [
            'method' => 'POST',
            'url' => route('settings.announcement'),
            'id' => 'announcement_settings_form',
            'model' => [
                'message' => Settings::get(SettingsHelper::S_ANNOUNCEMENT_MESSAGE),
                'display_class' => Settings::get(SettingsHelper::S_ANNOUNCEMENT_DISPLAY_CLASS)
            ]
        ]);

        $view->with('announcement_form', $announcementForm);

        return $view;
    }

    /**
     * @param SaveAnnouncementRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function saveAnnouncement(SaveAnnouncementRequest $request)
    {
        if (config('bap.demo')) {
            flash(trans('core::core.you_cant_do_that_its_demo'))->error();
            return redirect()->back();
        }

        $form = $this->form(AnnouncementForm::class);

        // Update Settings In Database
        Settings::set(SettingsHelper::S_ANNOUNCEMENT_MESSAGE, $form->getField('message')->getRawValue());
        Settings::set(SettingsHelper::S_ANNOUNCEMENT_DISPLAY_CLASS, $form->getField('display_class')->getRawValue());

        flash(trans('announcement::announcement.settings_updated'))->success();

        $cookie = \Cookie::forget('announcementModal');

        return redirect(route('settings.announcement'))->withCookie($cookie);
    }
}
