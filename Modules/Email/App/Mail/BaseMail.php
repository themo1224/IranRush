<?php

namespace Modules\Email\App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BaseMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject; 
    public $view;
    public $data;
    public $attachments;

    public function __construct($subject, $view, $data= [], $attachments= [])
    {
        $this->subject = $subject;
        $this->view = $view;
        $this->data = $data;
        $this->attachments = $attachments;
    }
    
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: $this->view,  // Uses Laravel's Markdown parser
            with: $this->data,
        );
    }

    public function attachments(): array
    {
        return $this->attachments ?? [];
    }
}
