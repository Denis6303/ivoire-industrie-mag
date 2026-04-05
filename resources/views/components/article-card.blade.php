@props([
    'article',
    'style' => '11', // post-style-11, 02, etc.
])
@php
    $cover = article_cover($article->cover_image);
@endphp
<div class="blog-post post-style-{{ $style }} mb-4">
    <div class="blog-image">
        @if ($cover)
            <a href="{{ route('articles.show', $article->slug) }}">
                <img class="img-fluid" src="{{ $cover }}" alt="{{ $article->cover_alt ?? $article->title }}">
            </a>
        @else
            <a href="{{ route('articles.show', $article->slug) }}" class="d-block ratio ratio-16x9 bg-light align-items-center justify-content-center text-muted">
                <span class="position-absolute top-50 start-50 translate-middle small">{{ config('app.name') }}</span>
            </a>
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
            <div class="blog-post-user mt-2">
                <span>par {{ $article->author->name ?? 'Rédaction' }}</span>
            </div>
        @endif
        <a class="btn-link" href="{{ route('articles.show', $article->slug) }}">Lire la suite</a>
    </div>
</div>
