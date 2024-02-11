<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EarningsUpdated extends Notification
{
    use Queueable;




    public $amount;

    public $balance;

    public function __construct($amount, $balance)
    {
        $this->amount = $amount;
        $this->balance = $balance;
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
        $appUrl = str_replace(['https://', 'http://'], '', config('app.url'));
        $earningUrl = "http://{$appUrl}/earning/";
        return (new MailMessage)
            ->subject('Your earnings have been updated!')
            ->line('New Earn: $' . $this->amount)
            ->line('Total Earnings: $' . $this->balance)
            ->action('View Details', $earningUrl)
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
