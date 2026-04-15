<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsletterSubscription;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewsletterMail;
use Illuminate\Validation\Rule;

class NewsletterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subscriptions = NewsletterSubscription::query()
            ->whereIn('status', ['pending', 'active'])
            ->orderByDesc('id')
            ->paginate(10);

        $counts = [
            'total' => NewsletterSubscription::whereIn('status', ['pending', 'active'])->count(),
            'active' => NewsletterSubscription::where('status', 'active')->count(),
            'pending' => NewsletterSubscription::where('status', 'pending')->count(),
            'unsubscribed' => NewsletterSubscription::where('status', 'unsubscribed')->count(),
        ];

        return view('admin.newsletter.index', compact('subscriptions', 'counts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.newsletter.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'edition' => ['required', 'string', 'max:100'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        $subscriptions = NewsletterSubscription::query()
            ->where('status', 'active')
            ->whereNotNull('token')
            ->get(['email', 'token']);

        if ($subscriptions->isEmpty()) {
            return back()->with('success', 'Aucun abonné actif à contacter.');
        }

        foreach ($subscriptions as $subscription) {
            Mail::to($subscription->email)->send(
                new NewsletterMail(
                    $data['edition'],
                    $data['subject'],
                    $data['body'],
                    route('newsletter.unsubscribe', ['token' => $subscription->token])
                )
            );
        }

        return redirect()->route('admin.newsletter.index')->with('success', 'Newsletter envoyée.');
    }

    /**
     * Display the specified resource.
     */
    public function show(NewsletterSubscription $newsletter)
    {
        return view('admin.newsletter.show', ['subscription' => $newsletter]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NewsletterSubscription $newsletter)
    {
        return view('admin.newsletter.edit', ['subscription' => $newsletter]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NewsletterSubscription $newsletter)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['pending', 'active', 'unsubscribed'])],
            'first_name' => ['nullable', 'string', 'max:255'],
        ]);

        $newsletter->update($data);

        return redirect()->route('admin.newsletter.index')->with('success', 'Abonnement mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NewsletterSubscription $newsletter)
    {
        $newsletter->delete();
        return back()->with('success', 'Abonnement supprimé.');
    }
}
