<?php

namespace Modules\Platform\Notifications\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Platform\Notifications\Entities\NotificationPlaceholder;


class CommentNotification extends Notification
{
    use Queueable;

    private $placeHolder;

    /**
     * Create a new notification instance.
     *
     * @return void
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', 'https://laravel.com')
            ->line('Thank you for using our application!');
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
            'color' => $this->placeHolder->getColor(),
            'icon' => $this->placeHolder->getIcon(),
            'content' => $this->placeHolder->getContent(),
            'author' => $this->placeHolder->getAuthor(),
            'url' => $this->placeHolder->getUrl(),
            'entity' => $this->placeHolder->getEntity()
        ];
    }
}
