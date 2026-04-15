@push('styles')
    <style>
        .sidebar-home-posts .blog-post.post-style-07 {
            padding: 12px;
            margin-bottom: 12px;
            align-items: center;
            justify-content: flex-start;
            column-gap: 0;
        }
        .sidebar-home-posts .blog-post.post-style-07 .post-image {
            flex: 0 0 88px;
            margin-right: 18px;
        }
        .sidebar-home-posts .blog-post.post-style-07 .blog-post-details {
            width: auto;
            flex: 1;
            min-width: 0;
            padding-left: 0;
        }
        .sidebar-home-posts .blog-post.post-style-07 .blog-post-details .blog-title {
            font-size: 16px;
            line-height: 1.3;
            padding-top: 0;
            margin-bottom: 0;
        }
        .sidebar-home-posts .blog-post.post-style-07 .blog-post-details .blog-post-meta {
            padding-top: 8px;
        }
        .sidebar-home-posts .blog-post.post-style-07 .sidebar-post-category {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #ff7800;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 4px;
        }
        .sidebar-home-posts .blog-post.post-style-07 .sidebar-post-category:hover {
            color: #041f42;
        }
    </style>
@endpush

@php
    $fallbackSquare = asset('images/ivm-placeholder-square.svg');
@endphp

<div class="widget post-widget">
    <h6 class="widget-title">{{ __('front.most_read') }}</h6>
    <div class="pt-2 sidebar-home-posts">
        @forelse ($sidebarPopular as $article)
            @php
                $cover = article_cover($article->cover_image);
                $articleTitle = article_i18n($article, 'title') ?: $article->title;
                $articleSlug = article_route_slug($article);
            @endphp
            <div class="blog-post post-style-07">
                <div class="post-image">
                    @if ($cover)
                        <a href="{{ route('articles.show', ['slug' => $articleSlug]) }}">
                            <span class="d-block ratio ratio-1x1 overflow-hidden rounded">
                                <img class="w-100 h-100" style="object-fit: cover;" src="{{ $cover }}" alt="{{ $article->cover_alt ?? $articleTitle }}" loading="lazy" onerror="this.onerror=null;this.src='{{ $fallbackSquare }}';">
                            </span>
                        </a>
                    @else
                        <a href="{{ route('articles.show', ['slug' => $articleSlug]) }}" class="d-block bg-light ratio ratio-1x1 rounded"></a>
                    @endif
                </div>
                <div class="blog-post-details">
                    @if ($article->category)
                        <a
                            class="sidebar-post-category"
                            href="{{ route('categories.show', ['slug' => $article->category->slug]) }}"
                            style="background: {{ $article->category->color ?: '#0d6efd' }}; color: #fff; padding: 2px 10px; border-radius: 999px;"
                        >{{ category_i18n($article->category) }}</a>
                    @endif
                    <h6 class="blog-title">
                        <a href="{{ route('articles.show', ['slug' => $articleSlug]) }}">{{ $articleTitle }}</a>
                    </h6>
                    @if ($article->published_at)
                        <div class="blog-post-meta">
                            <div class="blog-post-time">
                                <a href="{{ route('articles.show', ['slug' => $articleSlug]) }}"><i class="fa-solid fa-calendar-days"></i>{{ $article->published_at->translatedFormat('j M Y') }}</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-muted small mb-0">{{ __('sidebar.no_posts_tab') }}</p>
        @endforelse
    </div>
</div>
