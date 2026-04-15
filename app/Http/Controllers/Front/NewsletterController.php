<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Mail\NewsletterSubscriptionConfirmationMail;
use App\Models\NewsletterSubscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function subscribe(Request $request): RedirectResponse
    {
        $data = $request->validate(['email' => ['required', 'email']]);

        $subscription = NewsletterSubscription::updateOrCreate(
            ['email' => $data['email']],
            [
                'status' => 'pending',
                'token' => Str::uuid()->toString(),
                'confirmed_at' => null,
            ]
        );

        Mail::to($subscription->email)->send(
            new NewsletterSubscriptionConfirmationMail(
                route('newsletter.confirm', ['token' => $subscription->token]),
                route('newsletter.unsubscribe', ['token' => $subscription->token])
            )
        );

        return back()->with('success', 'Inscription enregistrée. Vérifie ton email pour confirmer.');
    }

    public function confirm(string $token): RedirectResponse
    {
        $updated = NewsletterSubscription::where('token', $token)->update([
            'status' => 'active',
            'confirmed_at' => now(),
        ]);

        if ($updated === 0) {
            return redirect()->route('home')->with('error', 'Lien de confirmation invalide.');
        }

        return redirect()->route('home')->with('success', 'Abonnement newsletter confirmé.');
    }

    public function unsubscribe(string $token): RedirectResponse
    {
        $updated = NewsletterSubscription::where('token', $token)->update(['status' => 'unsubscribed']);

        if ($updated === 0) {
            return redirect()->route('home')->with('error', 'Lien de désinscription invalide.');
        }

        return redirect()->route('home')->with('success', 'Désinscription prise en compte.');
    }
}
