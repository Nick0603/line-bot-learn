<?php

namespace App\Notifications;

use dustinhsiao21\LineNotify\LineChannel;
use dustinhsiao21\LineNotify\LineMessage;
use Illuminate\Notifications\Notification;

class LineNotify extends Notification
{
	private $message;

    public function __construct($message)
    {
    	$this->message = $message;
    }
    public function via($notifiable)
    {
        return [LineChannel::class];
    }

    public function toLine($notifiable)
    {
        return (new LineMessage())->message($message);
    }
}
