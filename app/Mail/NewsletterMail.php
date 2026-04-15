<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $editionLine,
        public string $subjectLine,
        public string $bodyLine,
        public ?string $unsubscribeUrl = null
    )
    {
    }

    public function build(): static
    {
        return $this->subject($this->subjectLine)
            ->view('emails.newsletter')
            ->with([
                'editionLine' => $this->editionLine,
                'subjectLine' => $this->subjectLine,
                'bodyLine' => $this->bodyLine,
                'unsubscribeUrl' => $this->unsubscribeUrl,
            ]);
    }
}

