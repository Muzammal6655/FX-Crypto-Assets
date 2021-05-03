<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $subject, $content, $filename = '', $csv = '')
    {
        $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        $this->to($email);
        $this->subject($subject);

        if (!empty($csv))
            $this->attach($csv, ['as' => $filename, 'mime' => 'text/csv']);

        $this->viewData = compact('content');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.template');
    }
}
