<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DepositPending extends Notification
{
    use Queueable;

    public $amount;
    public $name;

    public function __construct($amount, $name)
    {
        $this->amount = $amount;
        $this->name = $name;
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
    public function toMail($notifiable)
    {
        $appUrl = str_replace(['https://', 'http://'], '', config('app.url'));
        $depositUrl = "http://{$appUrl}/deposit/";

        return (new MailMessage)
            ->subject('Deposit Pending')
            ->greeting('Hello ' . $this->name)
            ->line('Your Deposit of $' . $this->amount . ' is currently pending.')
            ->line('You would be notified when deposit is completed')
            ->action('View Details', $depositUrl)
            ->line('If you have any questions or concerns, please contact support at support@' . $appUrl)
            ->salutation('Thank you for using our application!');
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
