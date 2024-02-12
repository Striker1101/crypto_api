<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DepositComplete extends Notification
{
    use Queueable;

    public $amount;
    public $name;
    public $wallet;

    public function __construct($amount, $name, $wallet)
    {
        $this->amount = $amount;
        $this->name = $name;
        $this->wallet = $wallet;
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
            ->subject('Deposit Completed')
            ->greeting('Hello ' . $this->name)
            ->line('Your Deposit of $' . $this->amount . ' from ' . $this->wallet . ' has been Processed Succesfully')
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
