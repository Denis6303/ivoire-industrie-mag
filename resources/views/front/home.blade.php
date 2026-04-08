@extends('layouts.front')

@section('title', 'Accueil')

@section('content')
    <section class="space-ptb">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mb-4 mb-lg-0">
                    @if ($featured)
                        @php
                            $f = $featured;
                            $cover = article_cover($f->cover_image);
                            $fallback16x9 = asset('images/ivm-placeholder-16x9.svg');
                        @endphp
                        <div class="blog-post post-style-11 home-featured-la-une mb-5">
                            <div class="blog-image">
                                @if ($cover)
                                    <a href="{{ route('articles.show', $f->slug) }}" class="d-block overflow-hidden rounded position-relative" style="height: 420px;">
                                        <span class="badge bg-danger position-absolute top-0 start-0 m-3" style="z-index: 2;">À la une</span>
                                        <img class="w-100 h-100" style="object-fit: cover;" src="{{ $cover }}" alt="{{ $f->cover_alt ?? $f->title }}" loading="eager" onerror="this.onerror=null;this.src='{{ $fallback16x9 }}';">
                                    </a>
                                @else
                                    <div class="ratio ratio-21x9 bg-light d-flex align-items-center justify-content-center text-muted">À la une</div>
                                @endif
                            </div>
                            <div class="blog-post-details">
                                @if ($f->category)
                                    <span class="badge badge-medium home-featured-la-une-badge" style="background: {{ $f->category->color ?: '#0d6efd' }}; color:#fff;">{{ $f->category->name }}</span>
                                @endif
                                <h2 class="blog-title mt-2">
                                    <a href="{{ route('articles.show', $f->slug) }}">{{ $f->title }}</a>
                                </h2>
                                <div class="blog-post-meta">
                                    @if ($f->published_at)
                                        <div class="blog-post-time">
                                            <span><i class="fa-solid fa-calendar-days"></i>{{ $f->published_at->translatedFormat('j F Y') }}</span>
                                        </div>
                                    @endif
                                </div>
                                @if ($f->excerpt)
                                    <p class="mt-3 home-featured-la-une-excerpt">{{ \Illuminate\Support\Str::limit(strip_tags($f->excerpt), (int) config('ivoireindustriemag.featured_excerpt_max_chars', 191), '…') }}</p>
                                @endif
                                @if ($f->author)
                                    <div class="blog-post-user mt-2"><span>par <span style="color:#243e5d;">{{ $f->signature ?: $f->author->name }}</span></span></div>
                                @endif
                                <a class="btn btn-primary btn-sm home-featured-la-une-btn mt-3" href="{{ route('articles.show', $f->slug) }}">Lire l’article</a>
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
                        <a href="{{ route('articles.index') }}" class="btn btn-primary">Tous les articles</a>
                    </div>

                    @if (isset($editorsPicks) && $editorsPicks->isNotEmpty())
                        <div class="section-title mb-4 mt-5">
                            <h2 class="mb-0"><i class="fa-solid fa-star me-2"></i>Sélection de la rédaction</h2>
                        </div>
                        <div class="row">
                            @foreach ($editorsPicks as $article)
                                <div class="col-md-6 mb-4">
                                    <x-article-card :article="$article" style="02" />
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if (isset($dataPosts) && $dataPosts->isNotEmpty())
                        <div class="section-title mb-4 mt-5">
                            <h2 class="mb-0"><i class="fa-solid fa-chart-line me-2"></i>Données & chiffres</h2>
                        </div>
                        <div class="row">
                            @foreach ($dataPosts as $article)
                                <div class="col-md-6 mb-4">
                                    <x-article-card :article="$article" style="02" />
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if (isset($sectors) && $sectors->isNotEmpty())
                        <div class="section-title mb-4 mt-5">
                            <h2 class="mb-0"><i class="fa-solid fa-industry me-2"></i>Secteurs à surveiller</h2>
                        </div>
                        <div class="row g-3">
                            @foreach ($sectors as $sector)
                                <div class="col-md-6 col-lg-4">
                                    <a href="{{ route('sectors.show', $sector->slug) }}" class="d-block border rounded p-3 h-100 text-decoration-none">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <strong class="text-dark">{{ $sector->name }}</strong>
                                            @if ($sector->color)
                                                <span class="badge" style="background: {{ $sector->color }};">&nbsp;</span>
                                            @endif
                                        </div>
                                        <div class="small text-muted">
                                            {{ $sector->articles_count ?? 0 }} articles · {{ $sector->companies_count ?? 0 }} entreprises · {{ $sector->projects_count ?? 0 }} projets
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if (isset($featuredCompanies) && $featuredCompanies->isNotEmpty())
                        <div class="section-title mb-4 mt-5">
                            <h2 class="mb-0"><i class="fa-solid fa-building me-2"></i>Entreprises mises en avant</h2>
                        </div>
                        <div class="row g-3">
                            @foreach ($featuredCompanies as $company)
                                <div class="col-md-6 col-lg-3">
                                    <a href="{{ route('companies.show', $company->slug) }}" class="d-block border rounded p-3 h-100 text-decoration-none">
                                        <div class="d-flex align-items-center mb-2">
                                            @if ($company->logo)
                                                <img src="{{ $company->logo }}" alt="{{ $company->name }}" class="rounded me-2 bg-white" style="width:42px;height:42px;object-fit:contain;" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('images/ivm-placeholder-square.svg') }}';">
                                            @endif
                                            <strong class="text-dark">{{ \Illuminate\Support\Str::limit($company->name, 32) }}</strong>
                                        </div>
                                        @if ($company->description)
                                            <div class="small text-muted">{{ \Illuminate\Support\Str::limit(strip_tags($company->description), 90) }}</div>
                                        @endif
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if (isset($projects) && $projects->isNotEmpty())
                        <div class="section-title mb-4 mt-5">
                            <h2 class="mb-0"><i class="fa-solid fa-helmet-safety me-2"></i>Projets industriels</h2>
                        </div>
                        <div class="row">
                            @foreach ($projects as $project)
                                <div class="col-md-6 mb-4">
                                    <div class="border rounded p-4 h-100 bg-white">
                                        <h3 class="h6 mb-2">{{ $project->name }}</h3>
                                        @if ($project->location)
                                            <div class="small text-muted mb-2"><i class="fa-solid fa-location-dot"></i> {{ $project->location }}</div>
                                        @endif
                                        @if ($project->description)
                                            <p class="mb-0">{{ \Illuminate\Support\Str::limit(strip_tags($project->description), 170) }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center">
                            <a href="{{ route('projects.index') }}" class="btn btn-outline-primary">Voir tous les projets</a>
                        </div>
                    @endif

                    @if (isset($moreArticles) && $moreArticles->isNotEmpty())
                        <div class="section-title mb-4 mt-5">
                            <h2 class="mb-0"><i class="fa-solid fa-layer-group me-2"></i>À lire aussi</h2>
                        </div>
                        <div class="row">
                            @foreach ($moreArticles as $article)
                                <div class="col-md-6 mb-4">
                                    <x-article-card :article="$article" style="02" />
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="col-lg-4">
                    <div class="sidebar">
                        @include('front.partials.sidebar-home-related-posts')
                        @include('front.partials.sidebar-top-categories-and-tags')
                        <div class="widget mt-4">
                            <h6 class="widget-title">Entreprises à suivre</h6>
                            @forelse ($companies as $company)
                                <div class="d-flex mb-3 align-items-center border-bottom pb-3">
                                    @if ($company->logo)
                                        <img src="{{ $company->logo }}" alt="{{ $company->name }}" class="rounded me-3 bg-white" style="width:56px;height:56px;object-fit:contain;" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('images/ivm-placeholder-square.svg') }}';">
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
                        @include('front.partials.sidebar-home-follow-social')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
