<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class users extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;

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
        $subject = 'Email Confirmation!';
        return $this->view('view.name')
        ->from('admin@admin.com', 'admin')
        ->cc($this->data['email'], $this->data['name'])
        ->bcc($this->data['email'], $this->data['name'])
        ->replyTo('admin@admin.com', 'admin')
        ->subject($subject)
        ->with(['message' => $this->data['message']]);
    }
}
