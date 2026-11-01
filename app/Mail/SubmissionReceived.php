<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Tells the owner about a public submission. Plain text only, so visitor input
 * can't inject HTML or Markdown into the mail. Replying reaches the requester.
 */
class SubmissionReceived extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public ContactMessage $submission) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->submission->email, $this->submission->name)],
            subject: $this->submission->type === 'commission'
                ? 'New commission request from '.$this->submission->name
                : 'New message: '.$this->submission->subject,
        );
    }

    public function content(): Content
    {
        return new Content(text: 'mail.submission-received');
    }
}
