@props([
    'article',
    'style' => '11', // post-style-11, 02, etc.
])
@php
    $cover = article_cover($article->cover_image);
    $catColor = $article->category?->color ?: '#0d6efd';
    $fallback16x9 = asset('images/ivm-placeholder-16x9.svg');
    $articleTitle = article_i18n($article, 'title') ?: $article->title;
    $articleExcerpt = article_i18n($article, 'excerpt') ?: $article->excerpt;
    $articleSlug = article_route_slug($article);
@endphp
@if ($style === '11')
    <div class="blog-post post-style-11 mb-4 border rounded overflow-hidden">
        <div class="blog-image">
            <a href="{{ route('articles.show', ['slug' => $articleSlug]) }}" class="d-block overflow-hidden" style="height: 360px;" title="{{ $articleTitle }}" aria-label="{{ $articleTitle }}">
                <img
                    class="w-100 h-100"
                    style="object-fit: cover;"
                    src="{{ $cover ?: $fallback16x9 }}"
                    alt="{{ $article->cover_alt ?? $articleTitle }}"
                    loading="lazy"
                    onerror="this.onerror=null;this.src='{{ $fallback16x9 }}';"
                >
            </a>
        </div>
        <div class="blog-post-details p-4">
            @if ($article->category)
                <span class="badge badge-medium" style="background: {{ $catColor }}; color: #fff;">{{ $article->category->name }}</span>
            @endif
            <h4 class="blog-title mt-2 mb-2">
                <a href="{{ route('articles.show', ['slug' => $articleSlug]) }}">{{ $articleTitle }}</a>
            </h4>
            <div class="blog-post-meta">
                @if ($article->published_at)
                    <div class="blog-post-time">
                        <a href="{{ route('articles.show', ['slug' => $articleSlug]) }}"><i class="fa-solid fa-calendar-days"></i>{{ $article->published_at->translatedFormat('j M Y') }}</a>
                    </div>
                @endif
            </div>
            @if ($articleExcerpt)
                <p class="mt-3 mb-0">{{ \Illuminate\Support\Str::limit(strip_tags($articleExcerpt), 320) }}</p>
            @endif
            @if ($article->author)
                <div class="blog-post-user mt-2">
                    <span>{{ __('app.by') }} <span style="color:#243e5d;">{{ $article->signature ?: ($article->author->name ?? 'Rédaction') }}</span></span>
                </div>
            @endif
            <a class="btn-link d-inline-block mt-3" href="{{ route('articles.show', ['slug' => $articleSlug]) }}">{{ __('app.read_more') }}</a>
        </div>
    </div>
@else
    <div @class([
            'blog-post mb-4',
            'post-style-'.$style,
            'post-style-02--bordered' => $style === '02',
        ])>
        <div class="blog-image">
            @if ($cover)
                <a href="{{ route('articles.show', ['slug' => $articleSlug]) }}" class="d-block overflow-hidden rounded" style="height: 210px;" title="{{ $articleTitle }}" aria-label="{{ $articleTitle }}">
                    <img class="w-100 h-100" style="object-fit: cover;" src="{{ $cover }}" alt="{{ $article->cover_alt ?? $articleTitle }}" loading="lazy" onerror="this.onerror=null;this.src='{{ $fallback16x9 }}';">
                </a>
            @else
                <a href="{{ route('articles.show', ['slug' => $articleSlug]) }}" class="d-block bg-light rounded position-relative" style="height: 210px;" title="{{ $articleTitle }}" aria-label="{{ $articleTitle }}">
                    <span class="position-absolute top-50 start-50 translate-middle small text-muted">{{ config('app.name') }}</span>
                </a>
            @endif
        </div>
        <div class="blog-post-details">
            @if ($article->category)
                <span class="badge badge-medium" style="background: {{ $catColor }}; color: #fff;">{{ $article->category->name }}</span>
            @endif
            <h4 class="blog-title ivm-card-title-ellipsis">
                <a href="{{ route('articles.show', ['slug' => $articleSlug]) }}" title="{{ $articleTitle }}">{{ $articleTitle }}</a>
            </h4>
            <div class="blog-post-meta">
                @if ($article->published_at)
                    <div class="blog-post-time">
                        <a href="{{ route('articles.show', ['slug' => $articleSlug]) }}"><i class="fa-solid fa-calendar-days"></i>{{ $article->published_at->translatedFormat('j M Y') }}</a>
                    </div>
                @endif
            </div>
            @if ($articleExcerpt)
                <p>{{ \Illuminate\Support\Str::limit(strip_tags($articleExcerpt), 180) }}</p>
            @endif
            @if ($article->author)
                <div class="blog-post-user mt-2 mb-2">
                    <span>{{ __('app.by') }} <span style="color:#243e5d;">{{ $article->signature ?: ($article->author->name ?? 'Rédaction') }}</span></span>
                </div>
            @endif
            <a class="btn-link d-inline-block mt-2" href="{{ route('articles.show', ['slug' => $articleSlug]) }}">{{ __('app.read_more') }}</a>
        </div>
    </div>
@endif
