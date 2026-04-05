@extends('layouts.front')

@section('title', 'Accueil')

@section('content')
    <section class="space-ptb">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mb-4 mb-lg-0">
                    @if ($featured)
                        @php $f = $featured; $cover = article_cover($f->cover_image); @endphp
                        <div class="blog-post post-style-11 mb-5">
                            <div class="blog-image">
                                @if ($cover)
                                    <a href="{{ route('articles.show', $f->slug) }}">
                                        <img class="img-fluid" src="{{ $cover }}" alt="{{ $f->cover_alt ?? $f->title }}">
                                    </a>
                                @else
                                    <div class="ratio ratio-21x9 bg-light d-flex align-items-center justify-content-center text-muted">À la une</div>
                                @endif
                            </div>
                            <div class="blog-post-details">
                                @if ($f->category)
                                    <span class="badge badge-medium bg-primary">{{ $f->category->name }}</span>
                                @endif
                                <h2 class="blog-title mt-2">
                                    <a href="{{ route('articles.show', $f->slug) }}">{{ $f->title }}</a>
                                </h2>
                                <div class="blog-post-meta">
                                    @if ($f->author)
                                        <div class="blog-post-user"><span>par {{ $f->author->name }}</span></div>
                                    @endif
                                    @if ($f->published_at)
                                        <div class="blog-post-time">
                                            <span><i class="fa-solid fa-calendar-days"></i>{{ $f->published_at->translatedFormat('j F Y') }}</span>
                                        </div>
                                    @endif
                                </div>
                                @if ($f->excerpt)
                                    <p class="mt-3">{{ $f->excerpt }}</p>
                                @endif
                                <a class="btn btn-primary mt-2" href="{{ route('articles.show', $f->slug) }}">Lire l’article</a>
                            </div>
                        </div>
                    @endif

                    <div class="section-title mb-4">
                        <h2 class="mb-0"><i class="fa-solid fa-newspaper me-2"></i>Derniers articles</h2>
                    </div>
                    <div class="row">
                        @foreach ($latest as $article)
                            <div class="col-md-6 mb-4">
                                <x-article-card :article="$article" style="02" />
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('articles.index') }}" class="btn btn-outline-primary">Tous les articles</a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="sidebar">
                        <div class="widget">
                            <h6 class="widget-title">Entreprises à suivre</h6>
                            @forelse ($companies as $company)
                                <div class="d-flex mb-3 align-items-center border-bottom pb-3">
                                    @if ($company->logo)
                                        <img src="{{ $company->logo }}" alt="" class="rounded me-3" style="width:56px;height:56px;object-fit:contain;">
                                    @endif
                                    <div>
                                        <h6 class="mb-0"><a href="{{ route('companies.show', $company->slug) }}">{{ $company->name }}</a></h6>
                                        @if ($company->sector)
                                            <small class="text-muted">{{ $company->sector->name }}</small>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted small">Aucune entreprise pour le moment.</p>
                            @endforelse
                            <a href="{{ route('companies.index') }}" class="btn btn-sm btn-primary w-100">Annuaire</a>
                        </div>
                        <div class="widget newsletter-widget mt-4">
                            <h6 class="widget-title">Newsletter</h6>
                            <div class="newsletter">
                                <i class="fa-solid fa-envelope-open-text"></i>
                                <p>Recevez les temps forts de l’industrie.</p>
                                <form class="newsletter-box" method="POST" action="{{ route('newsletter.subscribe') }}">
                                    @csrf
                                    <input type="email" name="email" class="form-control" placeholder="Votre e-mail" required>
                                    <button type="submit" class="btn btn-primary w-100 mt-2">S’abonner</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
