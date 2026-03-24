<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function subscribe(Request $request): RedirectResponse
    {
        $data = $request->validate(['email' => ['required', 'email']]);

        NewsletterSubscription::updateOrCreate(
            ['email' => $data['email']],
            ['status' => 'pending', 'token' => Str::uuid()->toString()]
        );

        return back()->with('success', 'Inscription enregistrée. Vérifie ton email pour confirmer.');
    }

    public function confirm(string $token): RedirectResponse
    {
        NewsletterSubscription::where('token', $token)->update([
            'status' => 'active',
            'confirmed_at' => now(),
        ]);

        return redirect()->route('home')->with('success', 'Abonnement newsletter confirmé.');
    }

    public function unsubscribe(string $token): RedirectResponse
    {
        NewsletterSubscription::where('token', $token)->update(['status' => 'unsubscribed']);
        return redirect()->route('home')->with('success', 'Désinscription prise en compte.');
    }
}
