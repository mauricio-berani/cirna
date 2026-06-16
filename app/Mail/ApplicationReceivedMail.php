<?php

namespace App\Mail;

use App\Models\Recruitment\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class ApplicationReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Application $application) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('site.careers.email.subject', ['name' => $this->application->name]),
            replyTo: [new Address($this->application->email, $this->application->name)],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.application-received',
            with: [
                'application' => $this->application,
                'areaLabel' => $this->application->areaLabel(),
            ],
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $fileName = 'curriculo-'.Str::slug($this->application->name).'.pdf';

        return [
            Attachment::fromStorageDisk('private', $this->application->resume_path)
                ->as($fileName)
                ->withMime('application/pdf'),
        ];
    }
}
