<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TradeCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $chat;

    public function __construct($chat)
    {
        $this->chat = $chat;
    }

    public function build()
    {
        return $this->subject('取引が完了しました')
                    ->view('emails.trade_completed');
    }
}
