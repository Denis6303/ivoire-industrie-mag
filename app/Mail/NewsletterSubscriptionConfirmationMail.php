<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterSubscriptionConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $confirmUrl,
        public string $unsubscribeUrl
    ) {
    }

    public function build(): static
    {
        return $this->subject('Confirmez votre inscription à la newsletter')
            ->view('emails.newsletter-confirmation')
            ->with([
                'confirmUrl' => $this->confirmUrl,
                'unsubscribeUrl' => $this->unsubscribeUrl,
            ]);
    }
}
