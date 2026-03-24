<?php

namespace App\Services;

use App\Models\NewsletterSubscription;
use Illuminate\Support\Str;

class NewsletterService
{
    public function subscribe(string $email): NewsletterSubscription
    {
        return NewsletterSubscription::updateOrCreate(
            ['email' => $email],
            ['status' => 'pending', 'token' => Str::uuid()->toString()]
        );
    }
}
