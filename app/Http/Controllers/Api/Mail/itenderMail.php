<?php

namespace  App\Http\Controllers\Api\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class itenderMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $message;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.itenderMail')->subject($this->data['subj'])->with('data', $this->data);
    }
}
