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
                                    <a href="{{ route('articles.show', ['slug' => $f->slug]) }}" class="d-block overflow-hidden rounded position-relative" style="height: 420px;">
                                        <img class="w-100 h-100" style="object-fit: cover;" src="{{ $cover }}" alt="{{ $f->cover_alt ?? $f->title }}" loading="eager" onerror="this.onerror=null;this.src='{{ $fallback16x9 }}';">
                                    </a>
                                @else
                                    <div class="ratio ratio-21x9 bg-light d-flex align-items-center justify-content-center text-muted">{{ config('app.name') }}</div>
                                @endif
                            </div>
                            <div class="blog-post-details">
                                @if ($f->category)
                                    <span class="badge badge-medium home-featured-la-une-badge" style="background: {{ $f->category->color ?: '#0d6efd' }}; color:#fff;">{{ $f->category->name }}</span>
                                @endif
                                <h2 class="blog-title mt-2">
                                    <a href="{{ route('articles.show', ['slug' => $f->slug]) }}">{{ $f->title }}</a>
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
                                <a class="btn btn-primary btn-sm home-featured-la-une-btn mt-3" href="{{ route('articles.show', ['slug' => $f->slug]) }}">Lire l’article</a>
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

                    @if (isset($breves) && $breves->isNotEmpty())
                        <div class="ivm-section-sep"></div>
                        <div class="section-title mb-4">
                            <h2 class="mb-0 d-flex align-items-center ivm-section-title">
                                <span class="ivm-section-dot me-2" style="background:#ff7800;"></span>
                                <span>Brèves</span>
                            </h2>
                        </div>
                        <div class="row">
                            @foreach ($breves as $article)
                                <div class="col-md-6 mb-4">
                                    <x-article-card :article="$article" style="02" />
                                </div>
                            @endforeach
                        </div>
                        @if (($brevesTotal ?? 0) > $breves->count())
                            <div class="text-center mb-2">
                                <a href="{{ route('categories.show', ['slug' => 'breve']) }}" class="btn btn-primary btn-sm">Voir plus</a>
                            </div>
                        @endif
                    @endif

                    @if (isset($homeSections) && $homeSections->isNotEmpty())
                        @foreach ($homeSections as $section)
                            @php
                                /** @var \App\Models\Category $cat */
                                $cat = $section['category'];
                                $posts = $section['posts'];
                                $totalPosts = $section['total_posts'] ?? $posts->count();
                            @endphp
                            <div class="ivm-section-sep"></div>
                            <div class="section-title mb-4">
                                <h2 class="mb-0 d-flex align-items-center ivm-section-title">
                                    <span class="ivm-section-dot me-2" style="background: {{ $cat->color ?: '#ff7800' }};"></span>
                                    <span>{{ $cat->name }}</span>
                                </h2>
                            </div>
                            <div class="row">
                                @foreach ($posts as $article)
                                    <div class="col-md-6 mb-4">
                                        <x-article-card :article="$article" style="02" />
                                    </div>
                                @endforeach
                            </div>
                            @if ($totalPosts >= 3)
                                <div class="text-center mb-2">
                                    <a href="{{ route('categories.show', ['slug' => $cat->slug]) }}" class="btn btn-primary btn-sm">Voir plus</a>
                                </div>
                            @endif

                            @if ($cat->slug === 'zones-industrielles' && !empty($adInArticle))
                                <div class="my-4">
                                    @include('front.partials.ad-banner', ['ad' => $adInArticle, 'variant' => 'horizontal'])
                                </div>
                            @endif

                        @endforeach
                    @endif
                </div>
                <div class="col-lg-4">
                    <div class="sidebar">
                        @include('front.partials.sidebar-home-related-posts')
                        @if (!empty($adSidebar))
                            <div class="widget mb-4">
                                @include('front.partials.ad-banner', ['ad' => $adSidebar, 'variant' => 'vertical'])
                            </div>
                        @endif
                        <div class="widget mt-4">
                            <h6 class="widget-title">Entreprises qui bougent</h6>
                            @forelse ($companies as $company)
                                <div class="d-flex mb-3 align-items-center border-bottom pb-3">
                                    @if ($company->logo)
                                        <img src="{{ $company->logo }}" alt="{{ $company->name }}" class="rounded me-3 bg-white" style="width:56px;height:56px;object-fit:contain;" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('images/ivm-placeholder-square.svg') }}';">
                                    @endif
                                    <div>
                                        <h6 class="mb-0"><a href="{{ route('companies.show', ['slug' => $company->slug]) }}">{{ $company->name }}</a></h6>
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
                        @if (isset($featuredCompanies) && $featuredCompanies->isNotEmpty())
                            <div class="widget mt-4">
                                <h6 class="widget-title">Entreprises mises en avant</h6>
                                @foreach ($featuredCompanies->take(5) as $company)
                                    <div class="d-flex mb-3 align-items-center border-bottom pb-3">
                                        @if ($company->logo)
                                            <img src="{{ $company->logo }}" alt="{{ $company->name }}" class="rounded me-3 bg-white" style="width:56px;height:56px;object-fit:contain;" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('images/ivm-placeholder-square.svg') }}';">
                                        @endif
                                        <div>
                                            <h6 class="mb-0"><a href="{{ route('companies.show', ['slug' => $company->slug]) }}">{{ $company->name }}</a></h6>
                                            @if ($company->description)
                                                <small class="text-muted">{{ \Illuminate\Support\Str::limit(strip_tags($company->description), 62) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        @include('front.partials.sidebar-home-follow-social')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
