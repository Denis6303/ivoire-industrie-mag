@props([
    'article',
    'style' => '11', // post-style-11, 02, etc.
])
@php
    $cover = article_cover($article->cover_image);
@endphp
<div @class([
        'blog-post mb-4',
        'post-style-'.$style,
        'post-style-02--bordered' => $style === '02',
    ])>
    <div class="blog-image">
        @if ($cover)
            @if ($style === '02')
                <a href="{{ route('articles.show', $article->slug) }}" class="d-block overflow-hidden rounded" style="height: 210px;">
                    <img class="w-100 h-100" style="object-fit: cover;" src="{{ $cover }}" alt="{{ $article->cover_alt ?? $article->title }}">
                </a>
            @else
                <a href="{{ route('articles.show', $article->slug) }}" class="d-block ratio ratio-16x9 overflow-hidden rounded">
                    <img class="w-100 h-100" style="object-fit: cover;" src="{{ $cover }}" alt="{{ $article->cover_alt ?? $article->title }}">
                </a>
            @endif
        @else
            @if ($style === '02')
                <a href="{{ route('articles.show', $article->slug) }}" class="d-block bg-light rounded position-relative" style="height: 210px;">
                    <span class="position-absolute top-50 start-50 translate-middle small text-muted">{{ config('app.name') }}</span>
                </a>
            @else
                <a href="{{ route('articles.show', $article->slug) }}" class="d-block ratio ratio-16x9 bg-light align-items-center justify-content-center text-muted">
                    <span class="position-absolute top-50 start-50 translate-middle small">{{ config('app.name') }}</span>
                </a>
            @endif
        @endif
    </div>
    <div class="blog-post-details">
        @if ($article->category)
            <span class="badge badge-medium bg-primary">{{ $article->category->name }}</span>
        @endif
        <h4 class="blog-title">
            <a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
        </h4>
        <div class="blog-post-meta">
            @if ($article->published_at)
                <div class="blog-post-time">
                    <a href="#"><i class="fa-solid fa-calendar-days"></i>{{ $article->published_at->translatedFormat('j M Y') }}</a>
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
        <a class="btn-link d-inline-block mt-2" href="{{ route('articles.show', $article->slug) }}">Lire la suite</a>
    </div>
</div>
