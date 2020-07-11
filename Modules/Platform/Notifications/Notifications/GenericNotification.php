<?php

namespace Modules\Core\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notification;
use Modules\Notifications\Events\NotificationEvent;
use Modules\Platform\Notifications\Entities\NotificationConst;
use Modules\Platform\Notifications\Entities\NotificationPlaceholder;

/**
 *
 * Class GenericNotification
 *
 * @package Modules\Core\Notifications
 */
class GenericNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    private $placeHolder;

    /**
     * GenericNotification constructor.
     * @param NotificationPlaceholder $placeholder
     */
    public function __construct(NotificationPlaceholder $placeholder)
    {
        $this->placeHolder = $placeholder;


    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','broadcast'];
    }

    public function broadcastOn()
    {
        return 'user-notification-'.$this->placeHolder->getRecipient()->id;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {

        return [
            'id' => $this->id,
            'color' => $this->placeHolder->getColor(),
            'icon' => $this->placeHolder->getIcon(),
            'content' => $this->placeHolder->getContent(),
            'author' => $this->placeHolder->getAuthor(),
            'url' => $this->placeHolder->getUrl(),
            'entity' => $this->placeHolder->getEntity(),
            'more_content' => $this->placeHolder->getExtraContent(),
            'recipient' => $this->placeHolder->getRecipient()
        ];
    }
}
