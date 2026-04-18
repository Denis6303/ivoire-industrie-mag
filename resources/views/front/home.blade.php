@extends('layouts.front')

@section('title', __('front.home_title'))

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
                            $featuredTitle = article_i18n($f, 'title') ?: $f->title;
                            $featuredExcerpt = article_i18n($f, 'excerpt') ?: $f->excerpt;
                            $featuredSlug = article_route_slug($f);
                        @endphp
                        <div class="blog-post post-style-11 home-featured-la-une mb-5">
                            <div class="blog-image">
                                @if ($cover)
                                    <a href="{{ route('articles.show', ['slug' => $featuredSlug]) }}" class="d-block overflow-hidden rounded position-relative" style="height: 420px;">
                                        <img class="w-100 h-100" style="object-fit: cover;" src="{{ $cover }}" alt="{{ $f->cover_alt ?? $featuredTitle }}" loading="eager" onerror="this.onerror=null;this.src='{{ $fallback16x9 }}';">
                                    </a>
                                @else
                                    <div class="ratio ratio-21x9 bg-light d-flex align-items-center justify-content-center text-muted">{{ config('app.name') }}</div>
                                @endif
                            </div>
                            <div class="blog-post-details">
                                @if ($f->category)
                                    <span class="badge badge-medium home-featured-la-une-badge" style="background: {{ $f->category->color ?: '#0d6efd' }}; color:#fff;">{{ category_i18n($f->category) }}</span>
                                @endif
                                <h2 class="blog-title mt-2">
                                    <a href="{{ route('articles.show', ['slug' => $featuredSlug]) }}">{{ $featuredTitle }}</a>
                                </h2>
                                <div class="blog-post-meta">
                                    @if ($f->published_at)
                                        <div class="blog-post-time">
                                            <span><i class="fa-solid fa-calendar-days"></i>{{ $f->published_at->translatedFormat('j F Y') }}</span>
                                        </div>
                                    @endif
                                </div>
                                @if ($featuredExcerpt)
                                    <p class="mt-3 home-featured-la-une-excerpt">{{ \Illuminate\Support\Str::limit(strip_tags($featuredExcerpt), (int) config('ivoireindustriemag.featured_excerpt_max_chars', 191), '…') }}</p>
                                @endif
                                @if ($f->author)
                                    <div class="blog-post-user mt-2"><span style="color:#243e5d;">{{ $f->signature ?: $f->author->name }}</span></div>
                                @endif
                                <a class="btn btn-primary btn-sm home-featured-la-une-btn mt-3" href="{{ route('articles.show', ['slug' => $featuredSlug]) }}">{{ __('app.read_more') }}</a>
                            </div>
                        </div>
                    @endif

                    <div class="section-title mb-4">
                        <h2 class="mb-0"><i class="fa-solid fa-newspaper me-2"></i>{{ __('front.latest_articles') }}</h2>
                    </div>
                    <div class="row">
                        @foreach ($latest as $article)
                            <div class="col-md-6 mb-4">
                                <x-article-card :article="$article" style="02" />
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('articles.index') }}" class="btn btn-primary">{{ __('front.all_articles') }}</a>
                    </div>

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
                                    <span>{{ category_i18n($cat) }}</span>
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
                                    <a href="{{ route('categories.show', ['slug' => $cat->slug]) }}" class="btn btn-primary btn-sm">{{ __('front.see_more') }}</a>
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
                        @if (isset($breves) && $breves->isNotEmpty())
                            <div class="widget post-widget">
                                <h6 class="widget-title">{{ __('front.briefs') }}</h6>
                                <div class="pt-2 sidebar-home-posts">
                                    @foreach ($breves->take(4) as $breve)
                                        @php
                                            $breveCover = article_cover($breve->cover_image);
                                            $breveTitle = article_i18n($breve, 'title') ?: $breve->title;
                                            $breveSlug = article_route_slug($breve);
                                        @endphp
                                        <div class="blog-post post-style-07">
                                            <div class="post-image">
                                                @if ($breveCover)
                                                    <a href="{{ route('articles.show', ['slug' => $breveSlug]) }}">
                                                        <span class="d-block ratio ratio-1x1 overflow-hidden rounded">
                                                            <img class="w-100 h-100" style="object-fit: cover;" src="{{ $breveCover }}" alt="{{ $breve->cover_alt ?? $breveTitle }}" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('images/ivm-placeholder-square.svg') }}';">
                                                        </span>
                                                    </a>
                                                @else
                                                    <a href="{{ route('articles.show', ['slug' => $breveSlug]) }}" class="d-block bg-light ratio ratio-1x1 rounded"></a>
                                                @endif
                                            </div>
                                            <div class="blog-post-details">
                                                <span class="badge badge-medium mb-1" style="background:#dc3545;color:#fff;">Brève</span>
                                                <h6 class="blog-title">
                                                    <a href="{{ route('articles.show', ['slug' => $breveSlug]) }}">{{ $breveTitle }}</a>
                                                </h6>
                                                @if ($breve->published_at)
                                                    <div class="blog-post-meta">
                                                        <div class="blog-post-time">
                                                            <a href="{{ route('articles.show', ['slug' => $breveSlug]) }}"><i class="fa-solid fa-calendar-days"></i>{{ $breve->published_at->translatedFormat('j M Y') }}</a>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($breve->tags->isNotEmpty())
                                                    <div class="d-flex flex-wrap gap-1 mt-2">
                                                        @foreach ($breve->tags->take(3) as $tag)
                                                            <a href="{{ route('search', ['q' => $tag->name]) }}" class="badge text-bg-light text-decoration-none">#{{ $tag->name }}</a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @if (($brevesTotal ?? 0) > ($breves->count() ?? 0))
                                    <div class="text-center">
                                        <a href="{{ route('categories.show', ['slug' => 'breve']) }}" class="btn btn-primary btn-sm">{{ __('front.see_more') }}</a>
                                    </div>
                                @endif
                            </div>
                        @endif
                        @include('front.partials.sidebar-home-related-posts')
                        @if (!empty($adSidebar))
                            <div class="widget mb-4">
                                @include('front.partials.ad-banner', ['ad' => $adSidebar, 'variant' => 'vertical'])
                            </div>
                        @endif
                        <div class="widget mt-4">
                            <h6 class="widget-title">{{ __('front.moving_companies') }}</h6>
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
                                <p class="text-muted small">{{ __('front.no_companies') }}</p>
                            @endforelse
                            <a href="{{ route('companies.index') }}" class="btn btn-sm btn-primary w-100">{{ __('front.directory') }}</a>
                        </div>
                        @if (isset($featuredCompanies) && $featuredCompanies->isNotEmpty())
                            <div class="widget mt-4">
                                <h6 class="widget-title">{{ __('front.featured_companies') }}</h6>
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

    @include('front.partials.home-2im-tv')
@endsection
