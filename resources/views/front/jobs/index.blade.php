@extends('layouts.front')

@section('title', 'Emploi')

@section('content')
    @include('front.partials.page-header', ['title' => 'Emploi'])

    <section class="space-ptb">
        <div class="container">
            <div class="section-title mb-4">
                <h2 class="mb-0"><i class="fa-solid fa-briefcase me-2"></i>Offres d'emploi</h2>
            </div>
            <div class="row">
                @forelse($offers as $offer)
                    <div class="col-md-6 mb-4">
                        <div class="blog-post post-style-02 post-style-02--bordered mb-0">
                            <div class="blog-image">
                                <a href="{{ route('jobs.show', ['slug' => $offer->slug]) }}" class="d-block overflow-hidden rounded" style="height: 210px;">
                                    <img class="w-100 h-100" style="object-fit: cover;" src="{{ article_cover($offer->cover_image) ?: asset('images/ivm-placeholder-16x9.svg') }}" alt="{{ $offer->cover_alt ?? $offer->title }}">
                                </a>
                            </div>
                            <div class="blog-post-details">
                                <h4 class="blog-title mb-2">
                                    <a href="{{ route('jobs.show', ['slug' => $offer->slug]) }}">{{ $offer->title }}</a>
                                </h4>
                                @if($offer->published_at)
                                    <div class="blog-post-meta mb-2">
                                        <div class="blog-post-time"><span><i class="fa-solid fa-calendar-days"></i>{{ $offer->published_at->translatedFormat('j M Y') }}</span></div>
                                    </div>
                                @endif
                                @if($offer->signature)
                                    <div class="blog-post-user mb-2"><span>par <span style="color:#243e5d;">{{ $offer->signature }}</span></span></div>
                                @endif
                                <a class="btn-link d-inline-block" href="{{ route('jobs.show', ['slug' => $offer->slug]) }}">Voir l'offre</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12"><p class="text-muted">Aucune offre disponible pour le moment.</p></div>
                @endforelse
            </div>
            <div class="mt-2">{{ $offers->links() }}</div>
        </div>
    </section>
@endsection
