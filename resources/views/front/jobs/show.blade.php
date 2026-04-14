@extends('layouts.front')

@section('title', e($offer->title))

@section('content')
    @include('front.partials.page-header', ['title' => \Illuminate\Support\Str::limit($offer->title, 80)])

    <section class="space-ptb">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <article class="blog-post-info">
                        @if ($offer->cover_image)
                            <div class="blog-post-image mb-4">
                                <div class="overflow-hidden rounded" style="height: 360px;">
                                    <img class="w-100 h-100" style="object-fit: cover;" src="{{ article_cover($offer->cover_image) }}" alt="{{ $offer->cover_alt ?? $offer->title }}">
                                </div>
                            </div>
                        @endif

                        <h1 class="h3 mb-3">{{ $offer->title }}</h1>
                        <div class="d-flex gap-3 flex-wrap text-muted small mb-4">
                            @if ($offer->published_at)
                                <span><i class="fa-solid fa-calendar-days me-1"></i>{{ $offer->published_at->translatedFormat('j F Y') }}</span>
                            @endif
                            @if ($offer->signature)
                                <span>par <span style="color:#243e5d;">{{ $offer->signature }}</span></span>
                            @endif
                        </div>

                        <div class="article-body">
                            {!! article_body_html($offer->content) !!}
                        </div>
                    </article>
                </div>
                <div class="col-lg-4">
                    <div class="sidebar mt-4 mt-lg-0">
                        <div class="widget post-widget">
                            <h6 class="widget-title">Offres récentes</h6>
                            @forelse($related as $job)
                                <div class="border-bottom pb-3 mb-3">
                                    <h6 class="mb-1"><a href="{{ route('jobs.show', ['slug' => $job->slug]) }}">{{ $job->title }}</a></h6>
                                    @if($job->published_at)
                                        <small class="text-muted"><i class="fa-solid fa-calendar-days me-1"></i>{{ $job->published_at->translatedFormat('j M Y') }}</small>
                                    @endif
                                </div>
                            @empty
                                <p class="text-muted small mb-0">Aucune autre offre.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
