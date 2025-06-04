<?php

namespace App\Mail;

use App\Models\HiringRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HiringRequestNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $hiringRequest;


    /**
     * Create a new message instance.
     */
    public function __construct(HiringRequest $hiringRequest)
    {
        $this->hiringRequest = $hiringRequest;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Hiring Request Received',
        );
    }


    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.hiring.request',
            with: [
                'hiringRequest' => $this->hiringRequest,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
