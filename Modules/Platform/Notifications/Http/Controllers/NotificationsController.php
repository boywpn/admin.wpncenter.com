<?php

namespace Modules\Platform\Notifications\Http\Controllers;

use Modules\Notifications\Events\NotificationEvent;
use Modules\Platform\Core\Http\Controllers\AppBaseController;
use Modules\Platform\Notifications\Entities\NotificationConst;

class NotificationsController extends AppBaseController
{
    /**
     * Notifications index view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        $notifications = \Auth::user()->notifications()->orderBy('id', 'desc')->paginate(10);

        return view('notifications::notifications')->with('userNotifications', $notifications);
    }

    /**
     * Count new notifications
     * @return \Illuminate\Http\JsonResponse
     */
    public function countNewNotifications()
    {
        return \response()->json([
            'unreadNotifications' => \Auth::user()->unreadNotifications()->count()
        ]);
    }

    /**
     * Return top bar notifications as html in json
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function topBarNotifications()
    {
        return response()->json([
            'status' => 'success',
            'content' => \View::make('notifications::top-bar')->render()
        ]);
    }

    /**
     * Mark notification as seen or new
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead()
    {
        $notificationId = request()->get('id');

        $notification = \Auth::user()->notifications()->where('id', $notificationId)->first();

        if (empty($notification)) {
            return response()->json([
                'status' => 'error',
                'content' => trans('notifications::notifications.dont_exist')
            ]);
        }

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
            $checkedAs = 'read';
        } else {
            $notification->markAsUnread();
            $checkedAs = 'new';
        }

        event(new NotificationEvent(NotificationConst::RECOUNT_NEW_NOTIFICATIONS, \Auth::user()->id));

        return response()->json([
            'status' => 'success',
            'content' => $checkedAs
        ]);
    }

    public function delete()
    {

        $notificationId = request()->get('id');

        $notification = \Auth::user()->notifications()->where('id', $notificationId)->first();

        if (empty($notification)) {
            return response()->json([
                'status' => 'error',
                'content' => trans('notifications::notifications.dont_exist')
            ]);
        }

        $notification->delete();


        event(new NotificationEvent(NotificationConst::RECOUNT_NEW_NOTIFICATIONS, \Auth::user()->id));

        return response()->json([
            'status' => 'success',
            'content' => 'deleted'
        ]);
    }
}
