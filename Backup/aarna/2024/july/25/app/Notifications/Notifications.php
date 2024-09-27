<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class Notifications extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        try{
            return (new MailMessage)
                ->subject($this->details['type'])
                ->line(new HtmlString($this->details['mail_body']))
                ->action('View', $this->details['url']);
            // ->line('Thank you for using our application!');
        }catch(\Exception $e){

        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'data' => $this->details['body'],
            'name' => $this->details['name'],
            'type' => $this->details['type'],
            'url'  => $this->details['url']
         ];
    }
}
