<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Broadcasting\PrivateChannel;

class OffersNotification extends Notification
{
    use Queueable;
    private $offerData;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($offerData)
    {
        $this->offerData = $offerData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)                    
            ->name($this->offerData['title'])
            ->line($this->offerData['type'])
            ->action($this->offerData['text'], $this->offerData['url']);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'type' => $this->offerData['type'],
            'title' => $this->offerData['title'],
            'text' => $this->offerData['text'],
            'url' => $this->offerData['url'],
        ];
    }
     /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'data' => [
                'type' => $this->offerData['type'],
                'title' => $this->offerData['title'],
                'text' => $this->offerData['text'],
                'url' => $this->offerData['url'],
            ]
        ]);
    }

    public function broadcastOn()
    {
        return new PrivateChannel('offer-notification.' . $this->id);
    }
}
