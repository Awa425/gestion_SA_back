<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendEventNotification extends Notification
{
    use Queueable;
    public $event;

    /**
     * Create a new notification instance.
     */
    public function __construct($event)
    {
        $this->event = $event;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Un nouvel événement arrive bientôt')
                    ->greeting('Bonjour '.$notifiable->nom)
                    ->line('Nous espérons que cette journée vous trouve bien.')
                    ->line('Nous voulions simplement vous rappeler de l\'evenement à venir')
                    ->line('Les détails sont les suivants:')
                    ->line('Nom de l\'événement: ' . $this->event->subject)
                    ->line('Date: ' . $this->event->event_date)
                    ->line('Nous sommes impatients de vous accueillir et de partager ce moment spécial avec vous.')
                    ->line('Merci encore de participer à cet événement. Nous sommes ravis de vous avoir parmi nous.')
                    ->salutation('Cordialement,
                     Orange Digital Center');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
