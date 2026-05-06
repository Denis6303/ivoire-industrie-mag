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
                <div class="col-lg-8">
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
                                        <h4 class="blog-title mb-2" style="font-size:1.1rem;">
                                            <a href="{{ route('jobs.show', ['slug' => $offer->slug]) }}">{{ $offer->title }}</a>
                                        </h4>
                                        @if($offer->published_at)
                                            <div class="blog-post-meta mb-2">
                                                <div class="blog-post-time"><span><i class="fa-solid fa-calendar-days"></i>{{ $offer->published_at->translatedFormat('j M Y') }}</span></div>
                                            </div>
                                        @endif
                                        @if($offer->signature)
                                            <div class="blog-post-user mb-2">
                                                <span style="color:#ff7800;font-weight:700;">par</span>
                                                <span style="color:#243e5d;"> {{ $offer->signature }}</span>
                                            </div>
                                        @endif
                                        <a class="btn-link d-inline-block" href="{{ route('jobs.show', ['slug' => $offer->slug]) }}">Voir l'offre</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <i class="fa-solid fa-briefcase fa-3x mb-4 d-block" style="color:#e0e6ef;"></i>
                                <h3 class="fw-bold mb-2" style="color:#243e5d;">Aucune offre disponible pour le moment</h3>
                                <p class="text-muted mb-4" style="max-width:480px;margin:0 auto;">
                                    Revenez prochainement — de nouvelles opportunités dans le secteur industriel ivoirien seront publiées ici.
                                </p>
                                <a href="{{ route('home') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-house me-2"></i>Retour à l'accueil
                                </a>
                            </div>
                        @endforelse
                    </div>
                    <div class="mt-2">{{ $offers->links() }}</div>
                </div>
                <div class="col-lg-4">
                    <div class="sidebar mt-4 mt-lg-0">
                        <div class="widget post-widget">
                            <h6 class="widget-title">Vous recrutez ?</h6>
                            <div class="p-3 rounded" style="background:#f7f9fc;border:1px solid #e6ecf5;">
                                <p class="mb-2 fw-bold" style="color:#243e5d;font-size:1.72rem;">
                                    Entreprises, diffusez vos offres d'emploi sur 2IM.
                                </p>
                                <p class="mb-3" style="font-size:1rem;">
                                    Envoyez vos offres par e-mail ou WhatsApp.
                                </p>
                                <div class="d-grid gap-2">
                                    <a href="mailto:contact@ivoireindustriemag.com" class="btn btn-danger d-inline-flex align-items-center justify-content-center" style="white-space: nowrap;">
                                        <i class="fa-solid fa-envelope me-2"></i>contact@ivoireindustriemag.com
                                    </a>
                                    <a href="https://wa.me/2250101151908" target="_blank" rel="noopener noreferrer" class="btn btn-success">
                                        <i class="fa-brands fa-whatsapp me-2"></i>+225 01 01 151 908
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
