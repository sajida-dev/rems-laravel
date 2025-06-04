<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PropertyApplicationNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $application;
    public $property;
    public $type;
    public $user;

    public function __construct(Application $application)
    {
        $this->application = $application;
        $this->property = $application->property;
        $this->type = $application->type;
        $this->user = $application->user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New " . ucfirst($this->type) . " Application for {$this->property->title}"
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new_application',
            with: [
                'property' => $this->property,
                'user' => $this->user,
                'type' => ucfirst($this->type)
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
