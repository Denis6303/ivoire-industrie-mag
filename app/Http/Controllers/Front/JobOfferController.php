<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\JobOffer;

class JobOfferController extends Controller
{
    public function index(string $locale)
    {
        $offers = JobOffer::query()
            ->published()
            ->latest('published_at')
            ->paginate(12);

        return view('front.jobs.index', compact('offers'));
    }

    public function show(string $locale, string $slug)
    {
        $offer = JobOffer::query()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $related = JobOffer::query()
            ->published()
            ->where('id', '!=', $offer->id)
            ->latest('published_at')
            ->take(4)
            ->get();

        return view('front.jobs.show', compact('offer', 'related'));
    }
}
