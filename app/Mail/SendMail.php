<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Notifications\Messages\MailMessage;

class SendMail extends Mailable
{
    public $content;

    public $header;

    public $footer;

    public function __construct($content, $header, $footer)
    {
        $this->content = $content;
        $this->header = $header;
        $this->footer = $footer;
    }

    public function toArray($notifiable)
    {
        return [
            'content' => $this->content,
            'header' => $this->header,
            'footer' => $this->footer,
        ];
    }
    public function build()
    {
        return $this->markdown('Email.general')->with([
            'content' => $this->content,
            'header' => $this->header,
            'footer' => $this->footer,
        ]);
    }
}
