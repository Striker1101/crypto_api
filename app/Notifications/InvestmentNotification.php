<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvestmentNotification extends Notification
{
    use Queueable;

    public $amount;

    public $name;

    public $duration;

    public $plan;

    public function __construct($amount, $name, $duration, $plan)
    {
        $this->amount = $amount;
        $this->name = $name;
        $this->duration = $duration;
        $this->plan = $plan;
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
        $InvestUrl = "http://{$appUrl}/investment/";
        $UpdatPlanURL = "http://{$appUrl}/update_plan/";

        return (new MailMessage)
            ->subject('Investment Successful')
            ->greeting('Hello ' . $this->name)
            ->line('Congratulations! Your investment of $' . $this->amount . ' is successful.')
            ->line('Investment Details:')
            ->line('Amount: $' . $this->amount)
            ->line('Duration: ' . $this->duration . ' days')
            ->line('Plan: ' . $this->plan)
            ->action('View Investment', $InvestUrl)
            ->line('Thank you for choosing our platform for your investment needs.')
            ->action('Upgrade your Plan', $UpdatPlanURL);

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
