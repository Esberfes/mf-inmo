<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class Push extends Notification
{

    use Queueable;

    private $title;
    private $message;
    private $user;

    function __construct($title, $message, $user)
    {
        $this->title = $title;
        $this->message = $message;
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title($this->title." - ".$this->user)
            ->icon('/img/card-mf.jpg')
            ->body($this->message)
            ->action('View App', 'notification_action');
    }

}
