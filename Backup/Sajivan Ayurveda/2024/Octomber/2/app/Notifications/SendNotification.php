<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Broadcasting\PrivateChannel;

class SendNotification extends Notification
{
    use Queueable;
    private $notifyData;

    /**
     * Create a new notification instance.
     */
    public function __construct($notifyData)
    {
        $this->notifyData = $notifyData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->name($this->notifyData['title'])
                    ->action($this->notifyData['text'], $this->notifyData['url'])
                    ->line($this->notifyData['type']);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => $this->notifyData['type'],
            'title' => $this->notifyData['title'],
            'text' => $this->notifyData['text'],
            'url' => $this->notifyData['url'],
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'data' => [
                'type' => $this->notifyData['type'],
                'title' => $this->notifyData['title'],
                'text' => $this->notifyData['text'],
                'url' => $this->notifyData['url'],
            ]
        ]);
    }

    public function broadcastOn()
    {
        return new PrivateChannel('offer-notification.' . $this->id);
    }
}
