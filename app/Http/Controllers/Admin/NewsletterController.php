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
            ->where('status', 'active')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.newsletter.index', compact('subscriptions'));
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
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        $emails = NewsletterSubscription::query()
            ->where('status', 'active')
            ->pluck('email')
            ->filter()
            ->values();

        if ($emails->isEmpty()) {
            return back()->with('success', 'Aucun abonné actif à contacter.');
        }

        $fromEmail = config('mail.from.address') ?: null;
        $mail = new NewsletterMail($data['subject'], $data['body']);

        Mail::to($emails->all())
            ->send($mail);

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
