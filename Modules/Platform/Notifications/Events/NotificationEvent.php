<?php

namespace Modules\Notifications\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

/**
 * Class NotificationEvent
 *
 * @package Modules\Notifications\Events
 */
class NotificationEvent implements ShouldBroadcast
{
    use SerializesModels;

    public $message;

    public $userId;

    /**
     * NotificationEvent constructor.
     * @param $message
     * @param $userId
     */
    public function __construct($message, $userId)
    {
        $this->message = $message;
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['user-notification-'.$this->userId];
    }
}
