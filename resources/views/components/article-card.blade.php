@props([
    'article',
    'style' => '11', // post-style-11, 02, etc.
])
@php
    $cover = article_cover($article->cover_image);
    $catColor = $article->category?->color ?: '#0d6efd';
    $fallback16x9 = asset('images/ivm-placeholder-16x9.svg');
@endphp
@if ($style === '11')
    <div class="blog-post post-style-11 mb-4 border rounded overflow-hidden">
        <div class="blog-image">
            <a href="{{ route('articles.show', ['slug' => $article->slug]) }}" class="d-block overflow-hidden" style="height: 360px;" title="{{ $article->title }}" aria-label="{{ $article->title }}">
                <img
                    class="w-100 h-100"
                    style="object-fit: cover;"
                    src="{{ $cover ?: $fallback16x9 }}"
                    alt="{{ $article->cover_alt ?? $article->title }}"
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
                <a href="{{ route('articles.show', ['slug' => $article->slug]) }}">{{ $article->title }}</a>
            </h4>
            <div class="blog-post-meta">
                @if ($article->published_at)
                    <div class="blog-post-time">
                        <a href="{{ route('articles.show', ['slug' => $article->slug]) }}"><i class="fa-solid fa-calendar-days"></i>{{ $article->published_at->translatedFormat('j M Y') }}</a>
                    </div>
                @endif
            </div>
            @if ($article->excerpt)
                <p class="mt-3 mb-0">{{ \Illuminate\Support\Str::limit(strip_tags($article->excerpt), 320) }}</p>
            @endif
            @if ($article->author)
                <div class="blog-post-user mt-2">
                    <span>par <span style="color:#243e5d;">{{ $article->signature ?: ($article->author->name ?? 'Rédaction') }}</span></span>
                </div>
            @endif
            <a class="btn-link d-inline-block mt-3" href="{{ route('articles.show', ['slug' => $article->slug]) }}">Lire la suite</a>
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
                <a href="{{ route('articles.show', ['slug' => $article->slug]) }}" class="d-block overflow-hidden rounded" style="height: 210px;" title="{{ $article->title }}" aria-label="{{ $article->title }}">
                    <img class="w-100 h-100" style="object-fit: cover;" src="{{ $cover }}" alt="{{ $article->cover_alt ?? $article->title }}" loading="lazy" onerror="this.onerror=null;this.src='{{ $fallback16x9 }}';">
                </a>
            @else
                <a href="{{ route('articles.show', ['slug' => $article->slug]) }}" class="d-block bg-light rounded position-relative" style="height: 210px;" title="{{ $article->title }}" aria-label="{{ $article->title }}">
                    <span class="position-absolute top-50 start-50 translate-middle small text-muted">{{ config('app.name') }}</span>
                </a>
            @endif
        </div>
        <div class="blog-post-details">
            @if ($article->category)
                <span class="badge badge-medium" style="background: {{ $catColor }}; color: #fff;">{{ $article->category->name }}</span>
            @endif
            <h4 class="blog-title ivm-card-title-ellipsis">
                <a href="{{ route('articles.show', ['slug' => $article->slug]) }}" title="{{ $article->title }}">{{ $article->title }}</a>
            </h4>
            <div class="blog-post-meta">
                @if ($article->published_at)
                    <div class="blog-post-time">
                        <a href="{{ route('articles.show', ['slug' => $article->slug]) }}"><i class="fa-solid fa-calendar-days"></i>{{ $article->published_at->translatedFormat('j M Y') }}</a>
                    </div>
                @endif
            </div>
            @if ($article->excerpt)
                <p>{{ \Illuminate\Support\Str::limit($article->excerpt, 180) }}</p>
            @endif
            @if ($article->author)
                <div class="blog-post-user mt-2 mb-2">
                    <span>par <span style="color:#243e5d;">{{ $article->signature ?: ($article->author->name ?? 'Rédaction') }}</span></span>
                </div>
            @endif
            <a class="btn-link d-inline-block mt-2" href="{{ route('articles.show', ['slug' => $article->slug]) }}">Lire la suite</a>
        </div>
    </div>
@endif
